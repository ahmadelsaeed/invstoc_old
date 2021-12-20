<?php

function get_count($count)
{

    if ($count >=0 && $count <=999) {
        return $count;
    }
    elseif ($count >999 && $count <=9999) {
        $fst_chr = substr($count, 0, 1);
        $snd_chr = substr($count, 1, 1);
        return $fst_chr.'.'.$snd_chr.'K';
    }
    elseif ($count >9999 && $count <=99999) {
        $fst_chr = substr($count, 0, 2);
        $snd_chr = substr($count, 2, 1);
        return $fst_chr.'.'.$snd_chr.'K';
    }
    elseif ($count >99999 && $count <=999999) {
        $fst_chr = substr($count, 0, 3);
        $snd_chr = substr($count, 3, 1);
        return $fst_chr.'.'.$snd_chr.'K';
    }
    elseif ($count >999999 && $count <=9999999) {
        $fst_chr = substr($count, 0, 1);
        $snd_chr = substr($count, 1, 1);
        return $fst_chr.'.'.$snd_chr.'M';
    }


}


function convert_inside_obj_to_arr($objs, $col, $objc_type = "object",$each_element_in_arr = "") {
    $arr = array();
    foreach ($objs as $key => $obj) {
        if ($objc_type == "object") {
            $arr[] = $obj->$col;
        } else {

            if ($each_element_in_arr != "")
            {
                $arr[] = array($obj["$col"]);
            }
            else{
                $arr[] = $obj["$col"];
            }

        }
    }
    return $arr;
}

function string_safe($string,$replace="") {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>', '|',' ','+','&');
    return trim(str_replace($except, $replace, $string));
}

function string_safe2($string,$replace="") {
    $except = array('\\', '/', ':', '*', '?', '"', '<', '>','+','&');
    return trim(str_replace($except, $replace, $string));
}

function covert_inside_arr_to_table($arr){

    $return_html="<table>";

    foreach ($arr as $key => $value) {
        $return_html.="<tr>";
            $return_html.="<td>";
                $return_html.=$key;
            $return_html.="</td>";

            $return_html.="<td>";
                $return_html.=$value;
            $return_html.="</td>";

        $return_html.="</tr>";
    }

    $return_html.="</table>";

    return $return_html;
}

function get_all_nationalities_in_option_tag(){
    $html='
    <option value="">-- select one --</option>
    <option value="afghan">Afghan</option>
    <option value="albanian">Albanian</option>
    <option value="algerian">Algerian</option>
    <option value="american">American</option>
    <option value="andorran">Andorran</option>
    <option value="angolan">Angolan</option>
    <option value="antiguans">Antiguans</option>
    <option value="argentinean">Argentinean</option>
    <option value="armenian">Armenian</option>
    <option value="australian">Australian</option>
    <option value="austrian">Austrian</option>
    <option value="azerbaijani">Azerbaijani</option>
    <option value="bahamian">Bahamian</option>
    <option value="bahraini">Bahraini</option>
    <option value="bangladeshi">Bangladeshi</option>
    <option value="barbadian">Barbadian</option>
    <option value="barbudans">Barbudans</option>
    <option value="batswana">Batswana</option>
    <option value="belarusian">Belarusian</option>
    <option value="belgian">Belgian</option>
    <option value="belizean">Belizean</option>
    <option value="beninese">Beninese</option>
    <option value="bhutanese">Bhutanese</option>
    <option value="bolivian">Bolivian</option>
    <option value="bosnian">Bosnian</option>
    <option value="brazilian">Brazilian</option>
    <option value="british">British</option>
    <option value="bruneian">Bruneian</option>
    <option value="bulgarian">Bulgarian</option>
    <option value="burkinabe">Burkinabe</option>
    <option value="burmese">Burmese</option>
    <option value="burundian">Burundian</option>
    <option value="cambodian">Cambodian</option>
    <option value="cameroonian">Cameroonian</option>
    <option value="canadian">Canadian</option>
    <option value="cape verdean">Cape Verdean</option>
    <option value="central african">Central African</option>
    <option value="chadian">Chadian</option>
    <option value="chilean">Chilean</option>
    <option value="chinese">Chinese</option>
    <option value="colombian">Colombian</option>
    <option value="comoran">Comoran</option>
    <option value="congolese">Congolese</option>
    <option value="costa rican">Costa Rican</option>
    <option value="croatian">Croatian</option>
    <option value="cuban">Cuban</option>
    <option value="cypriot">Cypriot</option>
    <option value="czech">Czech</option>
    <option value="danish">Danish</option>
    <option value="djibouti">Djibouti</option>
    <option value="dominican">Dominican</option>
    <option value="dutch">Dutch</option>
    <option value="east timorese">East Timorese</option>
    <option value="ecuadorean">Ecuadorean</option>
    <option value="egyptian">Egyptian</option>
    <option value="emirian">Emirian</option>
    <option value="equatorial guinean">Equatorial Guinean</option>
    <option value="eritrean">Eritrean</option>
    <option value="estonian">Estonian</option>
    <option value="ethiopian">Ethiopian</option>
    <option value="fijian">Fijian</option>
    <option value="filipino">Filipino</option>
    <option value="finnish">Finnish</option>
    <option value="french">French</option>
    <option value="gabonese">Gabonese</option>
    <option value="gambian">Gambian</option>
    <option value="georgian">Georgian</option>
    <option value="german">German</option>
    <option value="ghanaian">Ghanaian</option>
    <option value="greek">Greek</option>
    <option value="grenadian">Grenadian</option>
    <option value="guatemalan">Guatemalan</option>
    <option value="guinea-bissauan">Guinea-Bissauan</option>
    <option value="guinean">Guinean</option>
    <option value="guyanese">Guyanese</option>
    <option value="haitian">Haitian</option>
    <option value="herzegovinian">Herzegovinian</option>
    <option value="honduran">Honduran</option>
    <option value="hungarian">Hungarian</option>
    <option value="icelander">Icelander</option>
    <option value="indian">Indian</option>
    <option value="indonesian">Indonesian</option>
    <option value="iranian">Iranian</option>
    <option value="iraqi">Iraqi</option>
    <option value="irish">Irish</option>
    <option value="israeli">Israeli</option>
    <option value="italian">Italian</option>
    <option value="ivorian">Ivorian</option>
    <option value="jamaican">Jamaican</option>
    <option value="japanese">Japanese</option>
    <option value="jordanian">Jordanian</option>
    <option value="kazakhstani">Kazakhstani</option>
    <option value="kenyan">Kenyan</option>
    <option value="kittian and nevisian">Kittian and Nevisian</option>
    <option value="kuwaiti">Kuwaiti</option>
    <option value="kyrgyz">Kyrgyz</option>
    <option value="laotian">Laotian</option>
    <option value="latvian">Latvian</option>
    <option value="lebanese">Lebanese</option>
    <option value="liberian">Liberian</option>
    <option value="libyan">Libyan</option>
    <option value="liechtensteiner">Liechtensteiner</option>
    <option value="lithuanian">Lithuanian</option>
    <option value="luxembourger">Luxembourger</option>
    <option value="macedonian">Macedonian</option>
    <option value="malagasy">Malagasy</option>
    <option value="malawian">Malawian</option>
    <option value="malaysian">Malaysian</option>
    <option value="maldivan">Maldivan</option>
    <option value="malian">Malian</option>
    <option value="maltese">Maltese</option>
    <option value="marshallese">Marshallese</option>
    <option value="mauritanian">Mauritanian</option>
    <option value="mauritian">Mauritian</option>
    <option value="mexican">Mexican</option>
    <option value="micronesian">Micronesian</option>
    <option value="moldovan">Moldovan</option>
    <option value="monacan">Monacan</option>
    <option value="mongolian">Mongolian</option>
    <option value="moroccan">Moroccan</option>
    <option value="mosotho">Mosotho</option>
    <option value="motswana">Motswana</option>
    <option value="mozambican">Mozambican</option>
    <option value="namibian">Namibian</option>
    <option value="nauruan">Nauruan</option>
    <option value="nepalese">Nepalese</option>
    <option value="new zealander">New Zealander</option>
    <option value="ni-vanuatu">Ni-Vanuatu</option>
    <option value="nicaraguan">Nicaraguan</option>
    <option value="nigerien">Nigerien</option>
    <option value="north korean">North Korean</option>
    <option value="northern irish">Northern Irish</option>
    <option value="norwegian">Norwegian</option>
    <option value="omani">Omani</option>
    <option value="pakistani">Pakistani</option>
    <option value="palauan">Palauan</option>
    <option value="panamanian">Panamanian</option>
    <option value="papua new guinean">Papua New Guinean</option>
    <option value="paraguayan">Paraguayan</option>
    <option value="peruvian">Peruvian</option>
    <option value="polish">Polish</option>
    <option value="portuguese">Portuguese</option>
    <option value="qatari">Qatari</option>
    <option value="romanian">Romanian</option>
    <option value="russian">Russian</option>
    <option value="rwandan">Rwandan</option>
    <option value="saint lucian">Saint Lucian</option>
    <option value="salvadoran">Salvadoran</option>
    <option value="samoan">Samoan</option>
    <option value="san marinese">San Marinese</option>
    <option value="sao tomean">Sao Tomean</option>
    <option value="saudi">Saudi</option>
    <option value="scottish">Scottish</option>
    <option value="senegalese">Senegalese</option>
    <option value="serbian">Serbian</option>
    <option value="seychellois">Seychellois</option>
    <option value="sierra leonean">Sierra Leonean</option>
    <option value="singaporean">Singaporean</option>
    <option value="slovakian">Slovakian</option>
    <option value="slovenian">Slovenian</option>
    <option value="solomon islander">Solomon Islander</option>
    <option value="somali">Somali</option>
    <option value="south african">South African</option>
    <option value="south korean">South Korean</option>
    <option value="spanish">Spanish</option>
    <option value="sri lankan">Sri Lankan</option>
    <option value="sudanese">Sudanese</option>
    <option value="surinamer">Surinamer</option>
    <option value="swazi">Swazi</option>
    <option value="swedish">Swedish</option>
    <option value="swiss">Swiss</option>
    <option value="syrian">Syrian</option>
    <option value="taiwanese">Taiwanese</option>
    <option value="tajik">Tajik</option>
    <option value="tanzanian">Tanzanian</option>
    <option value="thai">Thai</option>
    <option value="togolese">Togolese</option>
    <option value="tongan">Tongan</option>
    <option value="trinidadian or tobagonian">Trinidadian or Tobagonian</option>
    <option value="tunisian">Tunisian</option>
    <option value="turkish">Turkish</option>
    <option value="tuvaluan">Tuvaluan</option>
    <option value="ugandan">Ugandan</option>
    <option value="ukrainian">Ukrainian</option>
    <option value="uruguayan">Uruguayan</option>
    <option value="uzbekistani">Uzbekistani</option>
    <option value="venezuelan">Venezuelan</option>
    <option value="vietnamese">Vietnamese</option>
    <option value="welsh">Welsh</option>
    <option value="yemenite">Yemenite</option>
    <option value="zambian">Zambian</option>
    <option value="zimbabwean">Zimbabwean</option>';

    return $html;
}

function return_numbet_of_elments_based_on_arr_number($arr,$return_elment_val){


    if(!is_array($arr)||count($arr)==0){
        return [""];
    }

    $new_arr=[];
    for($i=0;$i<count($arr);$i++){
        $new_arr[]="$return_elment_val";
    }

    return $new_arr;
}

function get_profile_logo_or_default($path)
{
    $logo_img = url('/public_html/admin/images/profile.png');
    if(isset($path) && !empty($path))
    {
        $logo_img = url("/".$path);
    }

    return $logo_img;
}

function get_image_or_default($path)
{
    $logo_img = url('/public_html/admin/images/no_image.png');
    if(isset($path) && !empty($path)&&File::exists($path))
    {
        $logo_img = url("/".$path);
    }

    return $logo_img;
}

function user_default_image($user_obj)
{

    $img_path = url("public_html/front/images/male.jpg");
    if($user_obj->gender == "female")
    {
        $img_path = url("public_html/front/images/female.jpg");
    }

    if (!empty($user_obj->logo_path))
    {
        $img_path = url($user_obj->logo_path);
    }

    return $img_path;
}

function article_comment_image($user_image)
{

    $img_path = url("public_html/front/images/male.jpg");

    if (!empty($user_image))
    {
        $img_path = url($user_image);
    }

    return $img_path;
}

function get_warning($url = "", $text = "")
{

    $html = '<div class="row" style="margin-bottom: 20px;">
            <div class="col-md-4 col-md-offset-4">
                <img src="'.url("/public_html/admin/images/warning.png").'">
            </div>
        </div>
        <a href="'.$url.'" class="col-md-4 col-md-offset-3 btn btn-primary btn-lg">'.$text.'</a>';
    return $html;
}


// for matches selected
/*
 * [
        [1,2],
        [1,3],
        [2,1],
        [3,1],
        [2,3],
        [3,2]
    ]
 * */
function shuffle_array_ids($ids, $level_one = false)
{
    $new_ids_arr = [];
    if (is_array($ids) && count($ids))
    {
        // first
        foreach ($ids as $key => $id)
        {
            foreach ($ids as $key2 => $id2)
            {
                if ($id != $id2)
                {

                    if ($level_one)
                    {
                        if (!in_array([$id,$id2],$new_ids_arr) && !in_array([$id2,$id],$new_ids_arr))
                        {
                            $new_ids_arr[] = [$id,$id2];
                        }
                    }
                    else{
                        $new_ids_arr[] = [$id,$id2];
                    }

                }
            }
        }
    }
    else{
        $new_ids_arr = $ids;
    }

    return $new_ids_arr;
}

function check_permission($all_user_permissions,$index,$action){


    if(isset($all_user_permissions[$index])){
        $get_permission=$all_user_permissions[$index]->all();
    }


    if (isset($get_permission)&&is_array($get_permission) && count($get_permission))
    {
        $get_permission = $get_permission[0];
        if (isset($get_permission->$action) && $get_permission->$action)
        {
            return true;
        }

        $additional_permissions=json_decode($get_permission->additional_permissions);
        if (is_array($additional_permissions)&&in_array($action,$additional_permissions))
        {
            return true;
        }

    }

    return false;
}

function transform_underscore_text($text){
    return "<span style='text-transform: capitalize;'>".str_replace("_"," ",$text)."</span>";
}

function split_first_word_from_sentence($sentence, $other_words_tag)
{

    $string = "";
    $sentence = explode(" ",$sentence);
    $string .= $sentence[0]." ";
    $string .= "<$other_words_tag>";
    foreach ($sentence as $key => $value)
    {
        if($key > 0)
        {
            $string .= $value;
        }
    }
    $string .= "</$other_words_tag>";

    return $string;
}
