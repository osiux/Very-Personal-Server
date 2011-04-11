<?php 


/**
 * Choose which sites you want to check, some take more time than others and
 * will mess up your system. Filsonic takes a long time for many files!
 *
 * true: means you want to check it
 * false: means you dont want to check it
 */
define('CHECK_MEGAUPLOAD', true);
define('CHECK_HOTFILE', true);
define('CHECK_RAPIDSHARE', true);
define('CHECK_FILESONIC', true);
define('CHECK_FILESERVE', true);

//---------------------------------------------------------------------------------------------------------------------
//------------------The following are functions for the MU/RS/HF linkchecker modification------------------------------
//---------------------------------------------------------------------------------------------------------------------

$FLAGS = array();

//Sets all flags to default!
$FLAGS['array'] = array();
$FLAGS['dead'] = false;
$FLAGS['num_dead'] = 0;
$FLAGS['num_alive'] = 0;
$FLAGS['total_size'] = 0;
$FLAGS['total_links'] = 0;

function print_flags(&$FLAGS) {
    print $FLAGS['dead'] . "<br />";
    print $FLAGS['num_dead'] . "<br />";
    print $FLAGS['num_alive'] . "<br />";
    print $FLAGS['total_size'] . "<br />";
    print $FLAGS['total_links'] . "<br />";
}

function do_reg($text, $regex) {
    preg_match_all($regex, $text, $result, PREG_PATTERN_ORDER);
    return $result = $result[0];
}

function send_data($site, $data) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $site);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    curl_close($ch);
    return $return;
}

function send_links($array_of_links) { //$array_of_links is an array of mu links
    $link_checking_site = "http://www.megaupload.com/mgr_linkcheck.php?";

    $string_links = "";
    for ($i = 0; isset($array_of_links[$i]); $i++) {
        if ($i == 0) {
            $string_links .= "id" . $i . "=" . str_replace("http://www.megaupload.com/?d=", "", $array_of_links[$i]);
        } else {
            $string_links .= "&id" . $i . "=" . str_replace("http://www.megaupload.com/?d=", "", $array_of_links[$i]);
        }
    }
    return send_data($link_checking_site, $string_links);
}

function parse_link_data($string_of_link_data) {
    $megaupload_return_string = "/(&id[0-9]+=[0-9])(&s=[0-9]+&d=[0-9]+&n=[_a-zA-Z0-9\.:\- ]+)?/";
    $array_of_link_data = do_reg($string_of_link_data, $megaupload_return_string);
    return $array_of_link_data;
}

function change_code($text, &$FLAGS) {
    $text = preg_replace("/\[code:(.*?)\](.*?)\[\/code:(.*?)\]/s",
                    '[code:$1]$2[/code:$3]',
                    $text);

    $text = preg_replace("/&#58;/", ":", $text);
    $text = preg_replace("/&#46;/", ".", $text);

    $text = replace_links($text, $FLAGS);

    $text = preg_replace("/:/", "&#58;", $text);
    $text = preg_replace("/\./", "&#46;", $text);

    return $text;
}

function byte_converter($numb) {
    $num = 0;
    $type = " B";
    if ($numb < 1024) {
        $type = " B";
    } else if ($numb < 1048576) {
        $type = " KB";
        $num = $numb / 1024;
    } else if ($numb < 1073741824) {
        $type = " MB";
        $num = $numb / 1048576;
    } else {
        $type = " GB";
        $num = $numb / 1073741824;
    }

    return "(" . number_format($num, 2, '.', '') . $type . ")";
}

function get_info($text, &$FLAGS) {

    $megaupload_link_regex = "/http:\/\/www\.megaupload\.com\/\?d=[a-zA-Z0-9]+/";
    $megaupload_return_string = "/(&id[0-9]+=[0-9])(&s=[0-9]+&d=[0-9]+&n=[_a-zA-Z0-9\.:\- ]+)?/";

    $array_of_links = do_reg($text, $megaupload_link_regex);
    $string_of_link_info = send_links($array_of_links);

    $array_of_link_data = parse_link_data($string_of_link_info);

    $return_array = array();
    $return_text_for_array = "";

    for ($i = 0; isset($array_of_link_data[$i]); $i++) {
        parse_str($array_of_link_data[$i], $valueArray);
        $id = "id" . $i;

        if ($valueArray[$id] == 0) {
            $Image = "<img src=\"http://rambo9.tk/checkmark_green.png\" width=10></img>";
            $Size = $valueArray['s'];
            $Name = "<span style='color:#000'>" . $valueArray['n'] . "</span>";

            //TESTING 12/25/2010
            $FLAGS['num_alive'] += 1;
            $FLAGS['total_size'] += $Size;
            $FLAGS['total_links'] += 1;
        } else {
            $Image = "<img src=\"http://rambo9.tk/DEAD.jpg\" width=10></img>";
            $Size = '';
            $Name = 'Invalid Link';

            //TESTING 12/25/2010
            array_push($FLAGS['array'], $array_of_links[$i]);
            $FLAGS['num_dead'] += 1;
            $FLAGS['total_links'] += 1;
            $FLAGS['dead'] = true;
        }

        if ($Name == "Invalid Link") {
            $return_text_for_array = $Image . " " . "<span style='color:red'>" . $array_of_links[$i] . " "
                    . $Name . " " . $Size . "</span>";
                    $goes=FALSE;
        } else {
            $return_text_for_array = $Image . " " . $array_of_links[$i] . " " . $Name . " " . byte_converter($Size)
                    . "";
                    $goes=TRUE;
        }
        $return_array[$i]['info'] = $return_text_for_array;
        $return_array[$i]['goes'] = $goes;
    }
    return $return_array;
}


//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
$i=0;
//Sets all flags to default!
    $FLAGS['dead'] = false;
    $FLAGS['num_dead'] = 0;
    $FLAGS['num_alive'] = 0;
    $FLAGS['total_size'] = 0;
    $FLAGS['total_links'] = 0;

    $lecho=array();
foreach ($grabbedlinks as $k){
	$i++;
    $linkchkd=@get_info($k, $FLAGS);
	$lecho[$i]['info']=$linkchkd['0']['info'];
    $lecho[$i]['goes']=$linkchkd['0']['goes'];
    $lecho[$i]['url']=$k;
} 
$this->load->helper('form');
?>
<div class='links'>
    <?= form_open('dwnldr/doscript')?>
    <span class='namedwnld'>Name: </span>
    <?= form_input(array('name'=>'downtitle', 'value'=>$downtitle, 'class'=>'namedwnld')) ?>
    <hr />
    <table>
    <thead class='tlinfo'>
        <tr>
            <td>#</td>
            <td> Link info</td>
            <td>?</td>
        </tr>
    </thead>
    <tbody>
    <?php foreach($lecho as $k=>$v){?>
        <tr>
            <td class='numb'><?= $k; ?></td>
            <td class='linfo'><?= $v['info'] ?>
            <?= form_hidden('llink'.$k, $v['url']) ?></td>
            <td class='lcheck'><?= form_checkbox('lcheck'.$k, '1', TRUE) ?></td>
        </tr>
    <?php 
    $i=$k;
    };?>
    </tbody>
    </table>
    <div class='linkdown' >
    <?= form_hidden('tlinks', $i) ?>
    <?= form_submit('doscript', 'Do Scripts!')?>
    <?= form_close()?>
    </div>
</div>