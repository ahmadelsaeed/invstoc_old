<?php

function generate_ajax_datatable($data_url,$cols,$links,$labels){
    krsort($links);

    $html="";


    $html.="<script>".PHP_EOL;
        $html.="$(document).ready(function(){".PHP_EOL;
            $html.="$('#example').DataTable({".PHP_EOL;
                $html.='"processing": true,'.PHP_EOL;
                $html.='"serverSide": true,'.PHP_EOL;
                $html.='"ajax": "'.($data_url).'",'.PHP_EOL;
                $html.='"columns": [';
                    foreach($cols as $col){
                        $html.='{ "data": "'.$col.'" },';
                    }
                $html.='],'.PHP_EOL;

                $html.='"columnDefs": ['.PHP_EOL;

                    $target_val=1;
                    foreach ($links as $key_link=>$link){
                        $html.="{".PHP_EOL;
                            $html.='"targets": -'.($target_val++).','.PHP_EOL;
                            $html.='"data": null,'.PHP_EOL;
                            $html.='"defaultContent": "'.$link.'"'.PHP_EOL;
                        $html.="},".PHP_EOL;
                    }

                $html.="]".PHP_EOL;


            $html.="});".PHP_EOL;
        $html.="});".PHP_EOL;
    $html.="</script>".PHP_EOL;

    $html.='<table id="example" class="display" cellspacing="0" width="100%">'.PHP_EOL;
    $html.='<thead>'.PHP_EOL;
        $html.='<tr>'.PHP_EOL;
            foreach($labels as $label){
                $html.='<th>'.$label.'</th>'.PHP_EOL;
            }
        $html.='</tr>'.PHP_EOL;
    $html.='</thead>'.PHP_EOL;

    $html.='<tfoot>'.PHP_EOL;
        $html.='<tr>'.PHP_EOL;
            foreach($labels as $label){
                $html.='<th>'.$label.'</th>'.PHP_EOL;
            }
        $html.='</tr>'.PHP_EOL;
    $html.='</tfoot>'.PHP_EOL;


    $html.=''.PHP_EOL;
    $html.=''.PHP_EOL;
    $html.='</table>'.PHP_EOL;


    return $html;
}