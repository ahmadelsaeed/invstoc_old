<div style="font-family: Helvetica Neue, Helvetica, Helvetica, Arial, sans-serif;">
    <table style="width: 100%;">
        <tr>
            <td></td>
            <td bgcolor="#FFFFFF ">
                <div style="padding: 15px; max-width: 600px;margin: 0 auto;display: block; border-radius: 0px;padding: 0px; border: 1px solid lightseagreen;">
                    <table style="width: 100%;background: #2DA045 ;">
                        <tr>
                            <td></td>
                            <td>
                                <div>
                                    <table width="100%">
                                        <tr>
                                            <td rowspan="2" style="text-align:center;padding:10px;">
                                                <img style="float:left; "
                                                     src="{{url(SITE_LOGO)}}"
                                                />
                                                <span style="color:white;float:right;font-size: 13px;font-style: italic;margin-top: 20px; padding:10px; font-size: 14px; font-weight:normal;">
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </td>
                            <td></td>
                        </tr>
                    </table>
                    <table style="padding: 10px;font-size:14px; width:100%;">
                        <tr>
                            <td style="padding:10px;font-size:14px; width:100%;">
                                <p>
                                    {{welcome_label}} , {{$name}}
                                    <br>
                                    <br>
                                    This email is to inform you that your request to get referrer link is not approved,
                                    you can contact
                                    <a href="{{url("support")}}">support</a>
                                    for more details

                                    <br>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div align="center"
                                     style="font-size:12px; margin-top:20px; padding:5px; width:100%; background:#2DA045;color: #fff;">
                                    <ul style="list-style: none;margin: 0px;display: inline-block;">
                                        <?php

                                            $social_imgs = (array)json_decode(email_social_imgs);
                                            $social_links = (array)json_decode(email_social_links);
                                        ?>
                                        <?php if(isset($social_imgs) && is_array($social_imgs) && count($social_imgs)): ?>
                                            <?php foreach($social_imgs as $key => $social_img): ?>
                                                <li style="float: left;padding: 0px 5px;margin-bottom: 5px;text-align: center;width: 35px;height: 35px;line-height: 40px;border-radius: 2px;">
                                                    <a style="color: #FFF;text-decoration: none;width: 100%;padding: 5px;"
                                                       href="{{isset($social_links[$key])?$social_links[$key]:"#"}}" target="_blank">
                                                        <img src="<?= url("public_html/email/$social_img") ?>" width="24" />
                                                    </a>
                                                </li>
                                            <?php endforeach;?>
                                        <?php endif; ?>
                                    </ul>
                                    <br>
                                    <a href="https://www.invstoc.com" target="_blank"
                                       style="color:#fff; text-decoration: none;">© {{str_replace('"','',copyright)}}</a>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
</div>

