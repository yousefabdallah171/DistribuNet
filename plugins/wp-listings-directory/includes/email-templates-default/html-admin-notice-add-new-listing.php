<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    </head>
    <body style="margin: 0; padding: 0;">
        <div style="background-color: #eeeeef; padding: 50px 0;">
            <table style="max-width: 640px;" border="0" cellspacing="0" cellpadding="0" align="center">
                <tbody>
                    <tr>
                        <td align="center" valign="top"><!-- Header -->
                            <table id="template_header" border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td>
                                            <h1 id="logo" style="padding: 28px 24px; display: block; font-family: Arial; font-size: 30px; font-weight: bold; text-align: center; line-height: 150%;">New Listing Posted</h1>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top"><!-- Body -->
                            <table id="template_body" border="0" width="100%" cellspacing="0" cellpadding="0">
                                <tbody>
                                    <tr>
                                        <td id="mailtpl_body_bg" style="background-color: #fafafa;" valign="top"><!-- Content -->
                                            <table border="0" width="100%" cellspacing="0" cellpadding="20">
                                                <tbody>
                                                    <tr>
                                                        <td valign="top">Hello

                                                            New Listing "{{listing_title}}" is Posted by {{author}} this user you can review this listing as a admin after reviewing you can approved or disapproved as you want. please follow below for all information submitted by user while posting new listing.
                                                            <h5>Posted Listing Information</h5>
                                                            <table class="blueTable">
                                                                <thead>
                                                                    <tr>
                                                                        <th style="text-align: center;" colspan="2">Listing Information</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr>
                                                                        <td>listing title</td>
                                                                        <td>{{listing_title}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>listing status</td>
                                                                        <td>{{listing_status}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>listing type</td>
                                                                        <td>{{listing_type}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>location</td>
                                                                        <td>{{listing_location}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>publish date</td>
                                                                        <td>{{listing_publish_date}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>expiry date</td>
                                                                        <td>{{listing_expiry_date}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>featured</td>
                                                                        <td>{{listing_featured}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>posted by</td>
                                                                        <td>{{author}}</td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td>status</td>
                                                                        <td>{{status}}</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" valign="top">
                            <table id="template_footer" style="border-top: 1px solid #E2E2E2; background: #eee; -webkit-border-radius: 0px 0px 6px 6px; -o-border-radius: 0px 0px 6px 6px; -moz-border-radius: 0px 0px 6px 6px; border-radius: 0px 0px 6px 6px;" border="0" width="100%" cellspacing="0" cellpadding="10">
                                <tbody>
                                    <tr>
                                        <td valign="top">
                                            <table border="0" width="100%" cellspacing="0" cellpadding="10">
                                                <tbody>
                                                    <tr>
                                                        <td id="credit" style="border: 0; color: #777; font-family: Arial; font-size: 12px; line-height: 125%; text-align: center;" colspan="2" valign="middle"><a href="{{website_url}}">{{website_name}}</a></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </body>
</html>