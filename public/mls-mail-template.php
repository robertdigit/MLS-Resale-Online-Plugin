<?php
   // Set the background color, using the dynamic color or default if not set
   $purchase_url = 'https://clarkdigital.es/resales-online-plugin/#contactplugin';
   $dark_light_hide = get_option('mls_plugin_style_darklighthide');
	if ($dark_light_hide) { 
   $headerfooter_bgcolor = get_option('mls_plugin_dark_primary_color', '#0073e1');
   }else{
   $headerfooter_bgcolor = get_option('mls_plugin_primary_color', '#0073e1');
   }
   ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
   <head>
      <meta http-equiv="Content-Type" content="text/html;UTF-8" />
      <style>
         @media only screen and (max-width: 600px) {
         #header {
         width: 100% !important;
         margin: 0 auto !important;
         }
         }
      </style>
      <script type="text/javascript" src="https://gc.kis.v2.scr.kaspersky-labs.com/5F6E79CB-67B7-5D45-B373-C644A3E9F3DD/main.js" charset="UTF-8"></script>
   </head>
   <body style="margin: 0px; background-color: #F4F3F4; font-family: Helvetica, Arial, sans-serif; font-size:12px;" text="#444444" bgcolor="#F4F3F4" link="#21759B" alink="#21759B" vlink="#21759B" marginheight="0" topmargin="0" marginwidth="0" leftmargin="0">
      <div style="width:600px; margin:0px; padding:0px; margin:0 auto;">
         <table align="center" border="0" cellpadding="0" cellspacing="0" style="border: 1px solid #f2f2f2;background: #ffffff;" width="600">
            <tbody>
               <tr>
                  <td align="center" colspan="3" style="line-height:1px; font-size:1px; background-color: <?php echo $headerfooter_bgcolor; ?>; padding-left: 20px; padding-right: 20px; padding-top: 20px; padding-bottom: 20px; " valign="top">
                     <table align="center" border="0" cellpadding="0" cellspacing="0" style="width: 100%; margin: 0">
                        <tr>
                           <td align="center" height="30">
                              <span style="font-size: 32px; color: #fff;">
                              <?php if($mls_plugin_leadformmailheaderlogo){ ?>
                              <a style="text-decoration: none;" href="[_site_url]" target="_blank" rel="noopener">
                              <img style="height:30px" src="<?php echo esc_url($mls_plugin_leadformmailheaderlogo); ?>" alt="logo" />
                              </a>
                              <?php }else{ echo "Logo"; } ?>
                              </span>
                           </td>
                        </tr>
                     </table>
                  </td>
               </tr>
            <tr>
            <td height="10"></td>
          </tr>
               <tr>
                  <td align="center" colspan="3" valign="top" style="color:#222; padding: 20px 15px 10px 14px; font-size: 20px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; text-transform: uppercase;"><?php echo esc_html($mls_plugin_leadformmailheadertext); ?></td>
               </tr>
               <!-- Other fields remain unchanged -->
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Name</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($user_name); ?> </td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Property ID</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($property_ref); ?> </td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Phone</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($phone); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Email Address</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($email); ?> </td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Comments</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html(stripslashes($comments)); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Tour Type</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d; padding: 10px 15px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($personvideo); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Scheduled Time</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($lead_time); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Scheduled Date</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d;padding: 10px 15px;font-size: 16px; text-transform: capitalize; font-family: Arial, Helvetica, sans-serif; "><?php echo esc_html($scheduledate); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Type of Visitor</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($buyerseller); ?></td>
               </tr>
               <tr>
                  <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Preferred Language</td>
                  <td align="left" valign="top" width="478" style="color: #5c727d;padding: 10px 15px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($preferredlanguage); ?></td>
               </tr>
            <tr>
            <td height="30"></td>
          </tr>
               <tr>
                  <td align="center" colspan="3" height="30" style="color: #ffffff; background: <?php echo $headerfooter_bgcolor; ?>; font-size: 14px;font-family: Arial, Helvetica, sans-serif; padding: 15px " valign="middle">
                     <?php echo esc_html($mls_plugin_leadformmailfootertext); ?>
                  </td>
               </tr>
            </tbody>
         </table>
      </div>
   </body>
</html>