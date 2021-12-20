<?php

if (!function_exists('btm_dump')) {
    function btm_dump($var, $label = 'Dump', $echo = TRUE)
    {
        // Store dump in variable
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
        //$output = '<pre style="background: #FFFEEF; color: #000; border: 1px dotted #000; padding: 10px; margin: 10px 0; text-align: left;width: 50%;margin: 0 auto;">' . $label . ' => ' . $output . '</pre>';
        $output = '<pre style="text-align: left;direction: ltr;">' . $label . ' => ' . $output . '</pre>';

        // Output
        if ($echo == TRUE) {
            echo $output;
        } else {
            return $output;
        }
    }
}


if (!function_exists('dump_exit')) {
    function dump_exit($var, $label = 'Dump', $echo = TRUE)
    {
        dump($var, $label, $echo);
        exit;
    }
}

function extract_youtube_links($content, $change = "yes")
{
    if (preg_match_all('~(https://www\.youtube\.com/watch\?v=[%&=#\w-]*)~', $content, $m)) {
        if ($change == "yes") {
            $output = array();
            foreach ($m[0] as $key => $value) {
                $value = str_replace("watch", "embed", $value);
                $output[] = str_replace("?v=", "/", $value);
            }
            return ($output);
        }
        return $m[0];
    }
}

function convertCurrency($amount, $from, $to)
{
    $url = "https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
    $data = file_get_contents($url);
    preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
    if (!isset($converted[1])) {
        return 1;
    }
    $converted = preg_replace("/[^0-9.]/", "", $converted[1]);
    return round($converted, 2);
}

function generate_currency_codes()
{

    return "
        <select name='currency' class='form-control'>
            <option value='KWD'>Kuwaiti Dinar (KWD)</option>
            <option value='EGP'>Egyptian Pound (EGP)</option>
            <option value='AED'>United Arab Emirates Dirham (AED)</option>
            <option value='SAR'>Saudi Riyal (SAR)</option>
            <option value='QAR'>Qatari Rial (QAR)</option>
            <option value='DZD'>Algerian Dinar (DZD)</option>
            <option value='IQD'>Iraqi Dinar (IQD)</option>
            <option value='MAD'>Moroccan Dirham (MAD)</option>
            <option value='OMR'>Omani Rial (OMR)</option>
            <option value='LYD'>Libyan Dinar (LYD)</option>
            <option value='SDG'>Sudanese Pound (SDG)</option>
            <option value='TND'>Tunisian Dinar (TND)</option>
            <option value='TRY'>Turkish Lira (TRY)</option>
            <option value='SYP'>Syrian Pound (SYP)</option>
            <option value='YER'>Yemeni Rial (YER)</option>
            <option value='USD'>US Dollar ($)</option>
            <option value='LBP'>Lebanese Pound (LBP)</option>
            <option value='JOD'>Jordanian Dinar (JOD)</option>
            <option value='BHD'>Bahraini Dinar (BHD)</option>
            <option value='PKR'>Pakistani Rupee (PKR)</option>
            <option value='MRO'>Mauritanian Ouguiya (MRO)</option>
            <option value='SOS'>Somali Shilling (SOS)</option>
            <option value='DJF'>Djiboutian Franc (DJF)</option>
            <option value='GBP'>British Pound (£)</option>

            </select>";

}

function split_word_into_chars($word, $number_of_chars, $include_end_of_text = "yes")
{
    $number_of_chars = $number_of_chars / 3;

    $arr = str_split($word, 3);

    if(count($arr)<$number_of_chars){
        $number_of_chars=count($arr)-1;
    }

    $arr = array_slice($arr, 0, (int)$number_of_chars);


    if ($include_end_of_text == "yes") {
        $arr[] = " ...";
    }

    return implode("", $arr);
}

function split_word_into_chars_ar($word,$number_of_chars,$include_end_of_text="yes")
{
    $word = strip_tags($word);

    mb_internal_encoding("UTF-8"); // this IS A MUST!! PHP has trouble with multibyte

    $chars = array();
    for ($i = 0; $i < $number_of_chars; $i++ ) {
        $chars[] = mb_substr($word, $i, 1); // only one char to go to the array
    }

    if ($include_end_of_text=="yes"&&strlen($word)>$number_of_chars){
        $chars[]=" ...";
    }

    return implode("",$chars);
}


function ip_info($ip = NULL, $purpose = "location", $deep_detect = TRUE)
{
    $output = NULL;
    if (filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {
        $ip = $_SERVER["REMOTE_ADDR"];
        if ($deep_detect) {
            if (filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            if (filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
    }
    $purpose = str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
    $support = array("country", "countrycode", "state", "region", "city", "location", "address");
    $continents = array(
        "AF" => "Africa",
        "AN" => "Antarctica",
        "AS" => "Asia",
        "EU" => "Europe",
        "OC" => "Australia (Oceania)",
        "NA" => "North America",
        "SA" => "South America"
    );
    if (filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {
        $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));
        if (@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {
            switch ($purpose) {
                case "location":
                    $output = array(
                        "city" => @$ipdat->geoplugin_city,
                        "state" => @$ipdat->geoplugin_regionName,
                        "country" => @$ipdat->geoplugin_countryName,
                        "country_code" => @$ipdat->geoplugin_countryCode,
                        "continent" => @$continents[strtoupper($ipdat->geoplugin_continentCode)],
                        "continent_code" => @$ipdat->geoplugin_continentCode
                    );
                    break;
                case "address":
                    $address = array($ipdat->geoplugin_countryName);
                    if (@strlen($ipdat->geoplugin_regionName) >= 1)
                        $address[] = $ipdat->geoplugin_regionName;
                    if (@strlen($ipdat->geoplugin_city) >= 1)
                        $address[] = $ipdat->geoplugin_city;
                    $output = implode(", ", array_reverse($address));
                    break;
                case "city":
                    $output = @$ipdat->geoplugin_city;
                    break;
                case "state":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "region":
                    $output = @$ipdat->geoplugin_regionName;
                    break;
                case "country":
                    $output = @$ipdat->geoplugin_countryName;
                    break;
                case "countrycode":
                    $output = @$ipdat->geoplugin_countryCode;
                    break;
            }
        }
    }
    return $output;
}

function get_client_ip()
{
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if (isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if (isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if (isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

function cmp_price_value($a, $b)
{
    //return strcmp(doubleval($b->price), doubleval($a->price));
    return strcmp(doubleval($a->price), doubleval($b->price));
}

function array_from_post($fields)
{
    $data = array();
    if (is_array($fields)) {
        foreach ($fields as $field) {
            $data[$field] = "";
            if (isset($_POST["$field"])) {
                $data[$field] = $_POST["$field"];
            }
        }
    }

    return $data;
}

function get_adv($adv_obj, $img_width = "0px", $img_height = "0px")
{

    if (is_array($adv_obj) && isset($adv_obj[0])) {
        $adv_obj = $adv_obj[0];
    }

    if (!isset($adv_obj->ad_show)) {
        return "";
    }

    if ($adv_obj->ad_show == "script") {
        return $adv_obj->ad_script;
    } else {
        return "<a href='$adv_obj->ad_link' target='_blank'>
                        <img class='responsive-img' src='" . url("/" . $adv_obj->ad_img_path) . "' alt='$adv_obj->ad_img_alt' title='$adv_obj->ad_img_title' style='width:$img_width;height:$img_height;max-width:100%; ' />
                    </a>";
    }

}

function capitalize_string($string){
    $field_name=explode("_",$string);
    if(isset_and_array($field_name)){
        $field_name=array_map("ucfirst",$field_name);
        return implode(" ",$field_name);
    }
    else{
        return ucfirst($field_name);
    }
}

function show_content($content_json, $field_name)
{
    if (isset($content_json->{$field_name})) {
        return $content_json->{$field_name};
    } else {
        return capitalize_string($field_name);
    }
}


function split_word_into_chars_ar_with_more_link($word,$number_of_chars,$read_more_label=" ...")
{
    $word = strip_tags($word);

    $count_chars = strlen($word);

    mb_internal_encoding("UTF-8"); // this IS A MUST!! PHP has trouble with multibyte

    $chars = array();
    for ($i = 0; $i < $number_of_chars; $i++ ) {
        $chars[] = mb_substr($word, $i, 1); // only one char to go to the array
    }

    if ($count_chars > $number_of_chars)
    {
        $chars[]=" <a href='#' class='load_more_paragraph'>$read_more_label</a>";
    }


    return implode("",$chars);
}


function split_word_into_chars_ar_without_more_link($word,$number_of_chars,$read_more_label=" ...")
{
    $word = strip_tags($word);

    $count_chars = strlen($word);

    mb_internal_encoding("UTF-8"); // this IS A MUST!! PHP has trouble with multibyte

    $chars = array();
    for ($i = 0; $i < $number_of_chars; $i++ ) {
        $chars[] = mb_substr($word, $i, 1); // only one char to go to the array
    }

    if ($count_chars > $number_of_chars)
    {
        $chars[]=" $read_more_label";
    }


    return implode("",$chars);
}



function get_last_word_from_sentence($sentence){
    $sentence_arr=explode(" ",$sentence);
    if(is_array($sentence_arr)&&count($sentence_arr)){
        $last_word=$sentence_arr[count($sentence_arr)-1];
        unset($sentence_arr[count($sentence_arr)-1]);
        return [implode(" ",$sentence_arr),$last_word];
    }

    return [$sentence,$sentence];
}

function isset_and_array($var){

    return (isset($var)&&is_array($var)&&count($var));

}

function convert_youtube_link_to_lazy_frame($youtube_link=""){
    $embed=extract_youtube_links($youtube_link);
    if(isset_and_array($embed)){
        $embed=$embed[0];

        return '<iframe width="560" height="315" class="b-lazy"
                    data-src="'.$embed.'"
                    frameborder="0"
                    allowfullscreen>
                </iframe>';
    }
    return "";
}

function return_youtube_thumbnail($youtube_link=""){
    //https://img.youtube.com/vi/<insert-youtube-video-id-here>/0.jpg
    $youtube_code=explode("=",$youtube_link);
    if(isset_and_array($youtube_code)){
        $youtube_code=$youtube_code[1];

        return "https://img.youtube.com/vi/$youtube_code/0.jpg";
    }
    return "";
}



function intPart($float) {
    if ($float < -0.0000001)
        return ceil($float - 0.0000001);
    else
        return floor($float + 0.0000001);
}

function Greg2Hijri($day, $month, $year, $string = false) {
    $day = (int) $day;
    $month = (int) $month;
    $year = (int) $year;


    if (($year > 1582) or ( ($year == 1582) and ( $month > 10)) or ( ($year == 1582) and ( $month == 10) and ( $day > 14))) {
        $jd = intPart((1461 * ($year + 4800 + intPart(($month - 14) / 12))) / 4) + intPart((367 * ($month - 2 - 12 * (intPart(($month - 14) / 12)))) / 12) -
            intPart((3 * (intPart(($year + 4900 + intPart(($month - 14) / 12) ) / 100) ) ) / 4) + $day - 32075;
    } else {
        $jd = 367 * $year - intPart((7 * ($year + 5001 + intPart(($month - 9) / 7))) / 4) + intPart((275 * $month) / 9) + $day + 1729777;
    }


    $l = $jd - 1948440 + 10632;
    $n = intPart(($l - 1) / 10631);
    $l = $l - 10631 * $n + 354;
    $j = (intPart((10985 - $l) / 5316)) * (intPart((50 * $l) / 17719)) + (intPart($l / 5670)) * (intPart((43 * $l) / 15238));
    $l = $l - (intPart((30 - $j) / 15)) * (intPart((17719 * $j) / 50)) - (intPart($j / 16)) * (intPart((15238 * $j) / 43)) + 29;

    $month = intPart((24 * $l) / 709);
    $day = $l - intPart((709 * $month) / 24);
    $year = 30 * $n + $j - 30;

    $date = array();
    $date['year'] = $year;
    $date['month'] = $month;
    $date['day'] = $day;


    if (!$string)
        return $date;
    else
        return "{$year}-{$month}-{$day}";
}



function get_hegri_date($date=null){
    if($date==null){
        $date=time();
    }

    $hijriDate = Greg2Hijri(date("d",$date), date("m",$date), date("Y",$date));

    $hijriMonth = array("محرم", "صفر", "ربيع الأول", "ربيع الثانى ", "جماد الاول", "جماد الثانى", "رجب", "شعبان", "رمضان", "شوال", "ذى القعده", "ذى الحجه");

    $year = $hijriDate["year"];
    $month = $hijriMonth[$hijriDate["month"] - 1];
    $day = $hijriDate["day"]+1;

    return $day . " " . $month . " " . $year;
}


function k_to_c($temp) {
    if ( !is_numeric($temp) ) { return false; }
    return round(($temp - 273.15));
}


function dump_date($str_data="",$format="j/ n/ Y"){
    return date($format,strtotime($str_data));
}

function populate_trans_admin($keyword,$en_val,$ar_val){
    $arr["en"]=$en_val;
    $arr["ar"]=$ar_val;
    return $arr;
}

function translate_admin_panel($data,$classification,$keyword,$lang){
    if(
        isset($data)&&isset($keyword)&&isset($lang)&&isset($classification)&&
        isset($data[$classification])&&isset($data[$classification][$keyword])&&isset($data[$classification][$keyword][$lang])){
        return $data[$classification][$keyword][$lang];
    }

    return $keyword;
}


function get_currency_rates($currencies)
{
    $currencies = implode(',',$currencies);
    $url = "http://www.apilayer.net/api/live?access_key=db50c8310dfc2e825521804282d5227e&currencies=$currencies";
    $data = file_get_contents($url);
    if(!empty($data))
    {
        $data = json_decode($data);
        if (isset($data))
        {
            return $data;
        }

    }

    return "";
}

function search_text_url_old($full_text)
{
    // The Regular Expression filter
    $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
    $full_text = str_replace(chr(194)," ",$full_text);

    // Check if there is a url in the text
    if(preg_match($reg_exUrl, $full_text, $url)) {

        $matched_url = $url[0];
        $matched_url = strip_tags($matched_url);
        $matched_url = utf8_encode($matched_url);

        // make the urls hyper links
        $full_text = preg_replace($reg_exUrl, "<a href='$matched_url' target='_blank' >".$matched_url."</a> ", $full_text);

        $full_text = utf8_encode($full_text);
        $full_text = str_replace('>  />','>',$full_text);

        return $full_text;

    } else {

        // if no urls in the text just return the text
        return $full_text;
    }

}

function search_text_url($str) {
    $reg_exUrl = "/[^\"](http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";


    $str = str_replace(chr(194)," ",$str);
    $str = str_replace('http', ' http', $str);

    $urls = array();
    $urlsToReplace = array();
    if(preg_match_all($reg_exUrl, $str, $urls)) {
        $numOfMatches = count($urls[0]);
        $numOfUrlsToReplace = 0;
        for($i=0; $i<$numOfMatches; $i++) {
            $alreadyAdded = false;
            $numOfUrlsToReplace = count($urlsToReplace);
            for($j=0; $j<$numOfUrlsToReplace; $j++) {
                if($urlsToReplace[$j] == $urls[0][$i]) {
                    $alreadyAdded = true;
                }
            }
            if(!$alreadyAdded) {
                array_push($urlsToReplace, $urls[0][$i]);
            }
        }
        $numOfUrlsToReplace = count($urlsToReplace);
        for($i=0; $i<$numOfUrlsToReplace; $i++) {
            $href_link = $urlsToReplace[$i];
            $href_link = str_replace('<br','',$href_link);
            $href_link = str_replace(' ', '', $href_link);
            $str = str_replace($urlsToReplace[$i], "<a target='_blank' href='$href_link'>$href_link</a> ", $str);
        }

        $str = utf8_encode($str);
        $str = str_replace('>  />','>',$str);

        return $str;
    } else {
        return $str;
    }
}


function calc_order_diff_price($post_data)
{
    $get_expected_price = ($post_data->expected_price);
    $get_closed_price = ($post_data->closed_price);

    $get_expected_price_points = strlen(substr(strrchr($get_expected_price, "."), 1));
    $get_closed_price_points = strlen(substr(strrchr($get_closed_price, "."), 1));

    if($get_expected_price_points > 0)
    {
        $multiply_number = 1;
        for($ind = 0;$ind < $get_expected_price_points;$ind++)
        {
            $multiply_number *= 10;
        }
        $get_expected_price = ($get_expected_price * $multiply_number);
    }

    if($get_closed_price_points > 0)
    {
        $multiply_number = 1;
        for($ind = 0;$ind < $get_closed_price_points;$ind++)
        {
            $multiply_number *= 10;
        }
        $get_closed_price = ($get_closed_price * $multiply_number);
    }

    $diff_price = ($get_expected_price - $get_closed_price);
    $diff_price = round($diff_price,3);

    $diff_price = str_replace('-','',$diff_price);

    return $diff_price;
}
