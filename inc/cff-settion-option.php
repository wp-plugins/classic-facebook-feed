<?php 
	if (isset($_POST['cff-submit'])) {
		$msg = 0;
		$cff_setting = array();
		$cff_setting['cff_fb_app_id'] 			=	$_POST['cff_fb_app_id'];
		$cff_setting['cff_fb_app_secret_key']	=	$_POST['cff_fb_app_secret_key'];
		/*$cff_setting['cff_access_token']		=	$_POST['cff_access_token'];*/

		$options = get_option('cff_setting');
		if (empty($options)) {
			add_option('cff_setting', $cff_setting);
			$msg = 1;
		}
		else{
			update_option( 'cff_setting', $cff_setting );
			$msg = 1;
		}
	}
?>
<?php if ($msg==1) { ?>
	<div class="updated settings-error notice is-dismissible" id="setting-error-settings_updated"> 
		<p>
			<strong>Settings saved.</strong>
		</p>
	</div>
<?php } ?>

<div class="wrap">
    <h2>Classic Facebook Feed Setiing</h2>
    <div class="wrap">
            <h2>Facebook Options</h2>
            <?php $options = get_option('cff_setting'); ?>
            <form method="post" action="">
                <table class="form-table">
                    <tr valign="top"><th scope="row">App ID :</th>
                        <td>
                        	<input type="text" name="cff_fb_app_id" value="<?php echo $options['cff_fb_app_id']; ?>" />
                        </td>
                    </tr>
                    <tr valign="top"><th scope="row">App Secret :</th>
                        <td>
                        	<input type="text" name="cff_fb_app_secret_key" value="<?php echo $options['cff_fb_app_secret_key']; ?>" />
                        </td>
                    </tr>
                    <?php /* 
                    <tr valign="top"><th scope="row">Access Token :</th>
                        <td>
                        	<input type="text" name="cff_access_token" value="<?php echo $options['cff_access_token']; ?>" />
                        </td>
                    </tr>
                    */?>
                </table>
                <p class="submit">
                    <input type="submit" class="button-primary" name="cff-submit" value="<?php _e('Save Changes') ?>" />
                </p>
            </form>
        </div>
</div>
