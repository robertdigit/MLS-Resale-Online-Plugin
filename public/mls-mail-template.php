<?php

// Set the background color, using the dynamic color or default if not set
$background_color = !empty($mls_plugin_blue_color) ? $mls_plugin_blue_color : '#0073e1'; // Default background color
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html;UTF-8" />
    <style>
      td a {
        text-decoration: none;
      }
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
    <table border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#F4F3F4">
      <tbody>
        <tr>
          <td style="padding: 15px;">
            <center>
              <table width="550" cellspacing="0" cellpadding="0" align="center" bgcolor="#d9d9d9">
                <tbody style="background: #ffffff; background-position: left top; background-size: auto; background-repeat: repeat;">
                  <tr>
                    <td align="left">
                      <div style="border: solid 1px #d9d9d9;">
                        <table id="header" style="line-height: 1.6; font-size: 12px; font-family: Helvetica, Arial, sans-serif; border: solid 0px #d9d9d9; color: #444; width: 100% !important; margin: 0 auto !important;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                          <tbody style="background: <?php echo esc_attr($background_color); ?>; background-position: left top; background-size: auto; background-repeat: repeat;">
                            <tr>
                              <td style="display: block; line-height: 1; width: 100%; padding-top: 20px; padding-bottom: 20px; text-align: center;" valign="baseline">
                                <span style="font-size: 32px; color: #fff;">
									<?php if($mls_plugin_leadformmailheaderlogo){ ?>
                                  <a style="text-decoration: none;" href="[_site_url]" target="_blank" rel="noopener">
                                    <img style="width: 130px;" src="<?php echo esc_url($mls_plugin_leadformmailheaderlogo); ?>" alt="logo" />
                                  </a>
									<?php }else{ echo "Logo"; } ?>
                                </span>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                        <table id="content" style="background: #00000000; margin-top: 15px; margin-right: 30px; margin-left: 30px; margin-bottom: 15px; color: #fff; line-height: 1.6; font-size: 12px; font-family: Arial, sans-serif;" border="0" width="490" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                          <tbody>
                            <tr>
                              <td align="center" colspan="3" valign="top" style="color:#222; padding: 20px 14px 10px 14px; font-size: 20px; font-weight: bold; font-family: Arial, Helvetica, sans-serif; text-transform: uppercase;"><?php echo esc_html($mls_plugin_leadformmailheadertext); ?></td>
                            </tr>
                            <!-- Other fields remain unchanged -->
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Name</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($user_name); ?> </td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Property ID</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($property_ref); ?> </td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Phone</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($phone); ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Email Address</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($email); ?> </td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Comments</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html(stripslashes($comments)); ?></td>
                            </tr>
                            <tr>
                              <td align="left" valign="top" width="200" style="color: #5c727d; font-weight: bold; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">Tour Type</td>
                              <td align="left" valign="top" width="478" style="color: #5c727d; padding: 5px 14px; font-size: 16px; font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($personvideo); ?></td>
                            </tr>
                            <tr>
                                <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Scheduled Time</td>
                                <td align="left" valign="top" width="478" style="color: #5c727d;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($lead_time); ?></td>
                              </tr>

                              <tr>
                                <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Scheduled Date</td>
                                <td align="left" valign="top" width="478" style="color: #5c727d;padding: 5px 14px;font-size: 16px; text-transform: capitalize; font-family: Arial, Helvetica, sans-serif; "><?php echo esc_html($scheduledate); ?></td>
                              </tr>

                              <tr>
                                <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Type of Visitor</td>
                                <td align="left" valign="top" width="478" style="color: #5c727d;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($buyerseller); ?></td>
                              </tr>
							  <tr>
                                <td align="left" valign="top" width="200" style="color: #5c727d;font-weight: bold;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;">Preferred Language</td>
                                <td align="left" valign="top" width="478" style="color: #5c727d;padding: 5px 14px;font-size: 16px;font-family: Arial, Helvetica, sans-serif;"><?php echo esc_html($preferredlanguage); ?></td>
                              </tr>
                          </tbody>
                        </table>
                        
						  <table id="footer" style="line-height: 1.5;background: #0073e1; font-size: 12px; font-family: Arial, sans-serif; margin-right: 0px; margin-left: 0px;padding-top: 15px;" border="0" width="100%" cellspacing="0" cellpadding="0" bgcolor="#ffffff">
                        <tbody>
                          <tr style="font-size: 11px;">
                            <td style="padding-left: 0px; font-size: 16px; color: #fff;text-align: center;" colspan="2">
                              <?php echo esc_html($mls_plugin_leadformmailfootertext); ?>
                            </td>
                          </tr>
                          <tr>
                            <td style="color: #000;" colspan="2" height="15">.</td>
                          </tr>
                        </tbody>
                      </table>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </center>
          </td>
        </tr>
      </tbody>
    </table>
  </body>
</html>