<table style="background-color: #fff;width: 100%;padding: 30px 20px;">

    <!--header-->
    <tr>
        <td>
            <a href="http://www.seoera.com" target="_blank">
                <img src="{{url($obj->logo_img->path)}}">
            </a>
        </td>
    </tr>

    <!--END header-->

    <!--body-->
    <tr>
        <td>
            <?php if(isset($msg)): ?>
                {{$msg}}
            <?php endif; ?>
        </td>
    </tr>
    <!--END body-->

    <p style="text-align: center">{!! $obj->copyright !!}</p>

    <!--footer-->
    <tr>
        <td style="padding: 27px 20%;">
            <table>

                <?php if(isset($email_data)): ?>
                    <?php foreach($email_data as $key=>$value): ?>
                        <tr>
                            <?php
                                $label=$key;
                                if(isset($labels_data["$key"])){
                                    $label=$labels_data["$key"];
                                }
                            ?>
                            <td>{{capitalize_string($label)}}</td>

                            <?php if(is_object(json_decode($value))): ?>
                                <td>
                                    <?php foreach(json_decode($value) as $json_key=>$json_item): ?>
                                        <?php
                                            if(empty($json_item)){
                                                continue;
                                            }
                                        ?>
                                        <p>{{capitalize_string($json_key)}}:{{$json_item}}</p>
                                        <br>
                                    <?php endforeach;?>
                                </td>
                            <?php else: ?>
                                <td>{!! $value !!}</td>
                            <?php endif; ?>

                        </tr>
                    <?php endforeach;?>
                <?php endif; ?>

                <tr>
                    <td style="width: 50%;">
                        <ul style="list-style: none;margin: 0px;">
                            <?php if(isset($obj->email_social_imgs) && is_array($obj->email_social_imgs)
                                    && count($obj->email_social_imgs)): ?>
                                <?php foreach($obj->email_social_imgs as $key=>$social_img): ?>
                                    <li style="float: left;padding: 0px 5px;margin-bottom: 5px;text-align: center;width: 35px;height: 35px;line-height: 40px;border-radius: 2px;">
                                        <a style="color: #FFF;text-decoration: none;width: 100%;padding: 5px;" href="{{isset($obj->email_social_links[$key])?$obj->email_social_links[$key]:""}}" target="_blank">
                                            <img src="<?= url("public_html/email/$social_img") ?>" width="24" />
                                        </a>
                                    </li>
                                <?php endforeach;?>
                            <?php endif; ?>
                        </ul>
                    </td>
                    <td></td>
                </tr>

            </table>


        </td>
    </tr>
    <!--footer-->



</table>
