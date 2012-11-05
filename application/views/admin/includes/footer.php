</div>
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