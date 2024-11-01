<?php 

add_action( 'admin_enqueue_scripts', 'triggerbee_admin_enqueue_scripts' );
function triggerbee_admin_enqueue_scripts($hook)
{
    global $triggerbee_options_page_hook;
    global $triggerbee_version;
    
    if($hook != $triggerbee_options_page_hook) //settings_page_triggerbee_options_page 
        return;

    wp_enqueue_style('triggerbee_dashboard_css', plugins_url('dashboard/dashboard.css', __FILE__), array(), $triggerbee_version);
   
    //wp_enqueue_style('triggerbee_dashboard_css', "aaa".$hook."aaa", array(), $triggerbee_version);
}

$triggerbee_options_page_hook;

add_action('admin_menu', 'triggerbee_admin_menu');
function triggerbee_admin_menu()
{
    global $triggerbee_options_page_hook;
    
    $triggerbee_options_page_hook = add_options_page(
        'Triggerbee',
        'Triggerbee',
        'manage_options',
        'triggerbee_options_page', /*also forms slug of url for options page*/
        'triggerbee_options_echo');
	}

add_action('admin_init', 'triggerbee_admin_init');
function triggerbee_admin_init()
{
    register_setting('triggerbee_group', 'triggerbee', 'triggerbee_sanitize'); 
}

function triggerbee_sanitize($options)
{
    return $options;
}


$triggerbee_default_options = array('tracking_id' => '');



function triggerbee_options_echo()
{
    global $triggerbee_default_options;
    
    $options = wp_parse_args( get_option( 'triggerbee', $triggerbee_default_options ), $triggerbee_default_options );
      
?>
<div class="triggerbee_admin">

	<?php if( $options["tracking_id"] == ""  ) { ?>
	
	<div class="triggerbee_admin_nocode">
	
		<div class="logo">
    		<img src="https://triggerbee.com/wp-content/uploads/tbimages/2020/01/triggerbee-logo-black.svg" />
  		</div>
	
    	<div class="outer-box">
      		<div class="content">
    			<h1>Welcome to Triggerbee &#128029;</h1>
				<h3>Triggerbee is a personalization software that connects with your CRM and email database, and makes it easy to create personalized forms, promtions, surveys and content for your website without any code.</h3>
        		<p>Install Triggerbee by pasting your Triggerbee-ID in the box below.</p>
        		<form method="post" enctype="multipart/form-data" action="options.php" id="triggerbee-nocode-form">  
                	<?php settings_fields( 'triggerbee_group' ); ?>  
        		
        			<div id="id-validation">
        				Incorrect ID, please try again or <a href="https://help.triggerbee.com/article/456-install-triggerbee-with-wordpress">read the installation guide.</a>
        			</div>
      				<input id="triggerbee_id" type="number" class="text" placeholder="Your Triggerbee-ID here" name="triggerbee[tracking_id]" value="<?php echo $options["tracking_id"] ?>">
      				
    				<button type="button"  class="submit"  onclick="OnClickSubmit()" >START TRACKING</button>
					
					<script>
					function OnClickSubmit()
					{
						var triggerbee_id =document.getElementById("triggerbee_id");
						var idValidation = document.getElementById('id-validation');
						var form 		 = document.getElementById("triggerbee-nocode-form");
						
						if( triggerbee_id.value.length < 4 ) 
							idValidation.style.display = 'block';
						
						else
						{
							idValidation.style.display = 'none';
							form.submit();
						}
					}
					</script>
    			
    			</form>
    			<div id="loginLinks">
					<a href="https://app.triggerbee.com/">Login to Triggerbee</a> or
					<a href="https://triggerbee.com/try-triggerbee-free?utm_campaign=wp-plugin-directory-trial">Register for Free</a> 
				</div>
    		</div>
      	</div> 
  	</div>
  	
  	<?php } else { ?>
  	
  	<div class="triggerbee_admin_nocode">
	  	<div class="logo">
    		<img src="https://triggerbee.com/wp-content/uploads/tbimages/2020/01/triggerbee-logo-black.svg" />
  		</div>
    	<div class="outer-box">
      		<div class="content">
				<table id="installation">
					<tr>
						<td><img src="https://triggerbee.com/wp-content/uploads/tbimages/2021/11/Check-green.svg" style="width:15px; margin-top: 5px"></td>
						<td>Triggerbee installation active for ID:</td>
						<td><?php echo $options["tracking_id"] ?></td>
					</tr>
				</table>
				<table id="tbLinks">
					<tr>
					<td><a href="https://app.triggerbee.com" target="_blank">To Triggerbee App</a></td>
					<td><a href="https://help.triggerbee.com/article/529-usage-examples" target="_blank">Usage Examples</a></td>
					<td><a href="https://help.triggerbee.com" target="_blank">Need help?</a></td>
					<td><a onclick="document.getElementById('triggerbee_admin_code_track_id').value = ''; document.getElementById('triggerbee_code_form').submit();">Uninstall</a></td>
					</tr>
				</table>
        	</div>
			<h2 id="addons">Addons</h2>
			<div class="addon-boxes">
				<div class="cf7-area addon">
					<p class="addon-bold">Contact Form 7</p>
					<p class="addon-desc">Track form submissions and emails in Triggerbee and collect consent.</p>
					<div class="buttons">
						<input type="button" class="button-cf7 addon-button" value="Integrate" onclick="document.getElementById('overlay_cf7').style.display = 'block';">
					</div>
				</div>
				<div class="woocommerce-area addon">
					<p class="addon-bold">WooCommerce</p>
					<p class="addon-desc">Track eCom events and email addresses from WooCommerce in Triggerbee</p>
					<div class="buttons">
						<input type="button" class="button-woocommerce addon-button" value="Integrate" onclick="document.getElementById('overlay_woocommerce').style.display = 'block';">
						<p id="woocommerce_email_tracking" style="display: none">Activate Email Tracking by following <a href="https://help.triggerbee.com/article/456-install-triggerbee-with-wordpress#Woocommerce-NvDyo" target="_blank">these steps</a></p>
					</div>
					<div id="overlay_woocommerce" style="display: none">
					<?php 
						if (class_exists('Woocommerce')) {
							echo "<script>document.querySelector('.button-woocommerce').value='Event Tracking Active';
							document.querySelector('.button-woocommerce').classList.add('active');
							document.querySelector('.button-woocommerce').classList.remove('addon-button');
							document.getElementById('woocommerce_email_tracking').style.display='block';</script>";
						}else {
							echo "<p id='noWoocommerce'>No active WooCommerce plugin found</p>";
						}
					?>
				</div>
	  			</div>
			</div>
			<div class="bodge">
            	<form method="post" enctype="multipart/form-data" action="options.php" id="triggerbee_code_form">  
                	<?php settings_fields( 'triggerbee_group' ); ?>  
      				<input type="hidden" id="triggerbee_admin_code_track_id" class="text" name="triggerbee[tracking_id]" value="<?php echo $options["tracking_id"] ?>" />
                    <div class="overlay" id="overlay_cf7">
                        <div class="overlay_inner">
        				<?php 
                            $cf7Forms = get_posts( array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1) );
                        	   
                            if( count($cf7Forms) == 0 ) 
                            {
                                echo "<h3>No CF7 Forms found</h3>";
                                echo "<p>Create one, and map its Triggerbee fields here.</p>";
                            }
                            
                            else
                            {
                            	foreach ($cf7Forms as $form)
                            	{
                            	   $post_id = $form->ID;
                            	   $form_title = $form->post_title;
                            	   $post_content = $form->post_content;
                            	   
                            	   $form_title_safe = preg_replace("/[^A-Za-z0-9 ]/", '', $form_title );
                            	   
                            	   $options_id = "cf7_".$post_id;
                            	                              	   
                            	   echo "<input type='hidden' name='triggerbee[{$options_id}][__FORMNAME]' value='{$form_title_safe}'></input>";
                            	   
                            	   echo "<div class='table_section'>";
                            	   echo "<h2>({$post_id}) {$form_title}</h2>";
                            	   echo "<table><tr><th>Type</th><th>Name</th><th>Triggerbee</th></tr>";
                            	      
                            	   $re = "/(?<=\\[)([^\\]]+)/";
                        
                                   preg_match_all($re, $post_content, $matchesPatternOrder);
                                   $matches = array_unique($matchesPatternOrder[0]);
                                      
                                   foreach ($matches as $value)
                                   {
                                        $out = shortcode_parse_atts( $value );                     // {"0":"[sname","1":"val1","val2":"42","2":"]"}
                                            
                                        if( isset($out[0]) && isset($out[1]) ) 
                                        {
                                            $type = $out[0];   
                                            $name = $out[1];
                                                
                                            if( substr($type, -1) == "*" ) 
                                                $type = substr($type, 0, -1);
                                            
                                            $custom = preg_replace("/[^A-Za-z0-9 ]/", '', strToLower( $name) );
                                            if( is_numeric(substr($custom, 0, 1)) )
                                                $custom = "x".$custom;
                                           
                                            $options_form = isset($options[$options_id])? $options[$options_id] : array();
                                            
                                            if( ! isset($options_form[$name]) ) 
                                                $options_form[$name] = "";
                                            
                                            $selectname = "triggerbee[".$options_id."][".$name."]";
                                            
                                            if( $type != "submit" )
                                            {
                                                echo "<tr><td>{$type}</td><td>{$name}</td><td><select name='{$selectname}'>";
                    	                        echo "<option value='name' "         .selected( $options_form[$name], 'name',         false).">Name</option>";
            			                        echo "<option value='email' "        .selected( $options_form[$name], 'email',        false).">Email</option>";
            			                        echo "<option value='organization' " .selected( $options_form[$name], 'organization', false).">Organization</option>";
            			                        echo "<option value='telephone' "    .selected( $options_form[$name], 'telephone',    false).">Telephone</option>";
            			                        echo "<option value='title' "        .selected( $options_form[$name], 'title',        false).">Title</option>";
            			                        echo "<option value='username' "     .selected( $options_form[$name], 'username',     false).">Username</option>";
            			                        echo "<option value='' "             .selected( $options_form[$name], ''     ,        false)."> </option>";
            			                        echo "<option value='{$custom}' "    .selected( $options_form[$name], $custom,         false).">Custom: {$custom}</option>";
            			                        
            			                        if( $type == "checkbox" )
            			                            echo "<option value='__consent_gdpr' "    .selected( $options_form[$name], '__consent_gdpr',         false).">Consent (GDPR)</option>";
            			                        
            				                    echo "</select></td>";
                                            }
                                         }
                                   }
                                      
                                   echo "</table>";
                                   echo "</div>";
                            	}
                            }
                         ?>	
					<button type="submit" class="submit">Save</button>
					<div class="close" onclick="document.getElementById('overlay_cf7').style.display = 'none'">Cancel</div>
                    	 </div>
                    </div>
                </form>
            </div>
		</div>
  
		</div>
		
	</div>
 
  	<?php } ?>
  	
</div>
<?php
}
    
?>


