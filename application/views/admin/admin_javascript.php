<script type='text/javascript'>
	function deleteTag_old(id, name, count) {
	  var answer = confirm("Are you sure you want to delete tag '" + name + "'? If you do, it will be removed from the "+ count +" goods that use it.");

		if (answer)
		  $.post("<?php echo site_url('admin/deleteTag/') ?>",
				{ }, function(data)
				{ location.reload(); }
			);
	}

	function delete_alert_template(id, name) {
		var txt = "Are you sure you want to delete alert template '" + name + "'?";
		$.prompt(txt,{ callback: function(v,m,f){
				if (v)
					$.post("<?php echo site_url('admin/delete_alert_template/') ?>",
					{
						'template_id' : id
					}, function(data)
					{ location.reload(); }
			)
		},  buttons: { Ok: true, Cancel: false }, focus: 1 });
	}

    function alert_form_validation(id,v,m,f) {
      if(!v) return true;

      ta_title = m.children('#ta_name');
      ta_title_error = m.children('#ta_name_error');
      ta_title_error.html("");

      ta_title.css("border","");

      ta_body = m.children('#ta_body');
      ta_body_error = m.children('#ta_body_error');
      ta_body_error.html("");
      ta_body.css("border","");
      is_valid = true;

      if(f.ta_name == "")
      {
        ta_title.css("border","solid #ff0000 1px");
        ta_title_error.html('Enter a valid title');
        is_valid = false;
      }

      else
      {
        ta_title_error.html('Checking...');
        $.ajax({
          async: false,
          type: 'POST',
          url: "<?php echo site_url('/admin/is_term_unique/') ?>",
          data: 'template_name=' + f.ta_name+ '&template_id=' + id,
          success: function(data)
          {
            //alert(data);
            if(data == "false")
            {
             ta_title.css("border","solid #ff0000 1px");
             ta_title_error.html('Enter an unique title');
             is_valid = false;
            }
            else{
             ta_title_error.html('');
             is_valid = true;
            }
          }
          
        })
      }


      if(f.ta_body == "")
      {
        ta_body.css("border","solid #ff0000 1px");
        ta_body_error.html('Enter a valid alert template');
        is_valid = false;
      }

      return is_valid;

    }

	function update_alert_template(id, name, body){
		var txt = "Name <br><div id=\"ta_name_error\"></div><input type=\"text\" id=\"ta_name\" name=\"ta_name\" value=\""+name+"\" /><br>Subject<br><input type=\"text\" id=\"ta_subject\" name=\"ta_subject\" value=\""+name+"\" /><br>Body<br><div id=\"ta_body_error\"></div><textarea id=\"ta_body\" name=\"ta_body\">"+body+"</textarea>";

        if (id == 0)
        {
          $.prompt(txt,{ submit: function(v,m,f) {return alert_form_validation(id,v,m,f)}, callback: function(v,m,f){

    				if (v)
        				$.post("<?php echo site_url('/admin/add_alert_template/') ?>",
            			{ 
            				'template_name': f.ta_name,
            				'template_body': f.ta_body
            			},function(data)
                		{location.reload();}
           )
              
          },  buttons: { Add: true, Cancel: false }, focus: 1 });

        }

        else
        {
          $.prompt(txt,{ submit: function(v,m,f) {return alert_form_validation(id,v,m,f)}, callback: function(v,m,f){
                  if (v)
                    $.post("<?php echo site_url('/admin/edit_alert_template/') ?>",
                      { 
                      		'template_id': id,
                      		'template_name': f.ta_name,
                      		'template_body': f.ta_body
                      },
                      function(data)
                      { location.reload(); }
              )
          },  buttons: { Edit: true, Cancel: false }, focus: 1 });
        }
	}

	function deleteTag(id, name, count) {
		var txt = "Are you sure you want to delete tag '" + name + "'? If you do, it will be removed from the "+ count +" goods that use it.";
		$.prompt(txt,{ callback: function(v,m,f){
				if (v)
					$.post("<?php echo site_url('/admin/deleteTag/') ?>" + "/" + id,
					{ }, function(data)
					{ location.reload(); }
			)
		},  buttons: { Ok: true, Cancel: false }, focus: 1 });
	}

	function renameTag(id, name, count){
		var txt = "Rename tag '" + name + "' to <input type=\"text\" id=\"renameTo\" name=\"renameTo\" />";
		$.prompt(txt,{ callback: function(v,m,f){
				if (v)
					$.post("<?php echo site_url('/admin/renameTag/') ?>" + "/" + id + "/" + f.renameTo,
					{ }, function(data)
					{ location.reload(); }
			)
		},  buttons: { Ok: true, Cancel: false }, focus: 1 });
	}
	
	function mergeTag(id, name, count){
	
		$.post("<?php echo site_url('/admin/tagSelectList') ?>", {}, function(tagSelectList){

			var txt = "Merge tag '" + name + "' into <select id=\"mergeTo\" name=\"mergeTo\" >" + tagSelectList + "</select>";
			$.prompt(txt,{ callback: function(v,m,f){

					if (v)
						$.post("<?php echo site_url('/admin/mergeTag/') ?>" + "/" + id + "/" + f.mergeTo,
						{ }, function(data)
						{ location.reload(); }
				)

			},  buttons: { Ok: true, Cancel: false }, focus: 1 });

		});
		
	}

	function toggleUserDisable(id, name, currentStatus) {

		var txt = "Are you sure you want to disable user '" + name + "'?";

		if (currentStatus == "disabled")
			txt = "Are you sure you want to re-enable user '" + name + "'?";

		$.prompt(txt,{ callback: function(v,m,f){
				if (v)
					$.post("<?php echo site_url('/admin/toggleUserDisable/') ?>" + "/" + id,
					{ }, function(data)
					{ location.reload(); }
			)
		},  buttons: { Ok: true, Cancel: false }, focus: 1 });
	}
</script>