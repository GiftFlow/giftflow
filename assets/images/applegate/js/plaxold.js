/* Plax version 1.2.1 */

/*
  Copyright (c) 2011 Cameron McEfee

  Permission is hereby granted, free of charge, to any person obtaining
  a copy of this software and associated documentation files (the
  "Software"), to deal in the Software without restriction, including
  without limitation the rights to use, copy, modify, merge, publish,
  distribute, sublicense, and/or sell copies of the Software, and to
  permit persons to whom the Software is furnished to do so, subject to
  the following conditions:

  The above copyright notice and this permission notice shall be
  included in all copies or substantial portions of the Software.

  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
  EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
  MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
  NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
  LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
  OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
  WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

(function ($) {

  var maxfps          = 25,
      delay           = 1 / maxfps * 1000,
      lastRender      = new Date().getTime(),
      layers          = [],
      docWidth        = $(window).width(),
      docHeight       = $(window).height(),
      motionEnabled   = false,
      motionMax       = 1,
      motionAllowance = .05,
      movementCycles  = 0,
      motionData      = {
        "xArray"  : [0,0,0,0,0],
        "yArray"  : [0,0,0,0,0],
        "xMotion" : 0,
        "yMotion" : 0
      }

  $(window).resize(function() {
      docWidth  = $(window).width()
      docHeight = $(window).height()
  })

  // Public Methods
  $.fn.plaxify = function (params){

    return this.each(function () {
      var layerExistsAt = -1
      var layer         = {
        "xRange": $(this).data('xrange') || 0,
        "yRange": $(this).data('yrange') || 0,
        "invert": $(this).data('invert') || false,
        "background": $(this).data('background') || false
      }

      for (var i=0;i<layers.length;i++){
        if (this === layers[i].obj.get(0)){
          layerExistsAt = i
        }
      }

      for (var param in params) {
        if (layer[param] == 0) {
          layer[param] = params[param]
        }
      }

      layer.inversionFactor = (layer.invert ? -1 : 1) // inversion factor for calculations

      // Add an object to the list of things to parallax
      layer.obj    = $(this)
      if(layer.background) {
        // animate using the element's background
        pos = (layer.obj.css('background-position') || "0px 0px").split(/ /)
        if(pos.length != 2) {
          return
        }
        x = pos[0].match(/^((-?\d+)\s*px|0+\s*%|left)$/)
        y = pos[1].match(/^((-?\d+)\s*px|0+\s*%|top)$/)
        if(!x || !y) {
          // no can-doesville, babydoll, we need pixels or top/left as initial values (it mightbe possible to construct a temporary image from the background-image property and get the dimensions and run some numbers, but that'll almost definitely be slow)
          return
        }
        layer.startX = x[2] || 0
        layer.startY = y[2] || 0
      } else {
        // animate using the element's position
        if(layer.obj.css('right') != 'auto') {
          // positioned using the "right" propery, not "left", so we should mutate that in case the parent element resizes
          layer.startX = $(this.parentNode).width() - this.offsetLeft - layer.obj.width()
          layer.hProp = 'right'
          layer.xRange = -1 * layer.xRange
          layer.obj.css('left', 'auto')
        } else {
          layer.startX = this.offsetLeft
          layer.hProp = 'left'
        }
        if(layer.obj.css('bottom') != 'auto') {
          // positioned using the "bottom" propery, not "top", so we should mutate that in case the parent element resizes
          layer.startY = $(this.parentNode).height() - this.offsetTop - layer.obj.height()
          layer.vProp = 'bottom'
          layer.yRange = -1 * layer.yRange
          layer.obj.css('top', 'auto')
        } else {
          layer.startY = this.offsetTop
          layer.vProp = 'top'
        }
      }

      layer.startX -= layer.inversionFactor * Math.floor(layer.xRange/2)
      layer.startY -= layer.inversionFactor * Math.floor(layer.yRange/2)
      if(layerExistsAt >= 0){
        layers.splice(layerExistsAt,1,layer)
      } else {
        layers.push(layer)
      }
      
    })
  }


  // Get minimum value of an array
  //
  // arr - array to be tested
  //
  // returns the smallest value in the array

  function getMin(arr){
    return Math.min.apply({}, arr)
  }


  // Get maximum value of an array
  //
  // arr - array to be tested
  //
  // returns the largest value in the array

  function getMax(arr){
    return Math.max.apply({}, arr)
  }


  // Determine if the device has an accelerometer

  function moveable(){
    return window.DeviceMotionEvent != undefined
  }


  // Determine if the device is actually moving. If it is, enable motion based parallaxing.
  // Otherwise, use the mouse to parallax
  //
  // e - devicemotion event
  
  function detectMotion(e){
    if (new Date().getTime() < lastRender + delay) return

    if(moveable()){
      var accel= e.accelerationIncludingGravity,
          x = accel.x,
          y = accel.y
      if(motionData.xArray.length >= 5){
        motionData.xArray.shift()
      }
      if(motionData.yArray.length >= 5){
        motionData.yArray.shift()
      }
      motionData.xArray.push(x)
      motionData.yArray.push(y)

      motionData.xMotion = Math.round((getMax(motionData.xArray) - getMin(motionData.xArray))*1000)/1000
      motionData.yMotion = Math.round((getMax(motionData.yArray) - getMin(motionData.yArray))*1000)/1000

      if((motionData.xMotion > 1.5 || motionData.yMotion > 1.5)) {
        if(motionMax!=10){
          motionMax = 10
        }
      }

      // test for sustained motion
      if(motionData.xMotion > motionAllowance || motionData.yMotion > motionAllowance){
        movementCycles++;
      } else {
        movementCycles = 0;
      }

      if(movementCycles >= 5){
        motionEnabled = true
        $(document).unbind('mousemove.plax')
        //window.ondevicemotion = function(e){plaxifier(e)}

        $(window).bind('devicemotion', plaxifier(e))
      } else {
        motionEnabled = false
        $(window).unbind('devicemotion')
        $(document).bind('mousemove.plax', function (e) {
          plaxifier(e)
        })
      }
    }
  }


  // Move the elements in the `layers` array within their ranges, 
  // based on mouse or motion input 
  //
  // e - mousemove or devicemotion event

  function plaxifier(e) {
    if (new Date().getTime() < lastRender + delay) return
      lastRender = new Date().getTime()

    var x = e.pageX,
        y = e.pageY

    if(motionEnabled == true){
          // portrait(%2==0) or landscape
      var i = window.orientation ? (window.orientation + 180) % 360 / 90 : 2,
          accel= e.accelerationIncludingGravity,
          tmp_x = i%2==0 ? -accel.x : accel.y,
          tmp_y = i%2==0 ? accel.y : accel.x
      // facing up(>=2) or down
      x = i>=2 ? tmp_x : -tmp_x
      y = i>=2 ? tmp_y : -tmp_y

      // change value from a range of -x to x => 0 to 1
      x = (x+motionMax)/2
      y = (y+motionMax)/2
      
      // keep values within range
      if(x < 0 ){
        x = 0
      } else if( x > motionMax ) {
        x = motionMax
      }

      if(y < 0 ){
        y = 0
      } else if( y > motionMax ) {
        y = motionMax
      }

    }

    var hRatio = x/((motionEnabled == true) ? motionMax : docWidth),
        vRatio = y/((motionEnabled == true) ? motionMax : docHeight),
        layer, i

    for (i = layers.length; i--;) {
      layer = layers[i]
      newX = layer.startX + layer.inversionFactor*(layer.xRange*hRatio)
      newY = layer.startY + layer.inversionFactor*(layer.yRange*vRatio)
      if(layer.background) {
        layer.obj.css('background-position', newX+'px '+newY+'px')
      } else {
        layer.obj
          .css(layer.hProp, newX)
          .css(layer.vProp, newY)
      }
    }
  }

  $.plax = {
    // Activeate Plax
    enable: function(){
      $(document).bind('mousemove.plax', function (e) {
        plaxifier(e)
      })

      if(moveable()){
        window.ondevicemotion = function(e){detectMotion(e)}
      }

    },
    // Deactiveate Plax
    disable: function(){
      $(document).unbind('mousemove.plax')
      window.ondevicemotion = undefined
    }
  }

  if (typeof ender !== 'undefined') {
    $.ender($.fn, true)
  }

})(function () {
  return typeof jQuery !== 'undefined' ? jQuery : ender
}())
