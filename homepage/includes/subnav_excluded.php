<!-- TODO Need refactoring , why is this code not in the include_list.php? -->
<!-- Does not find the list anymore why??? -->
<?php
if (isset($_GET['species']) && isset($_GET['add'])) {
    $file = '../scripts/exclude_species_list.txt';
    $str = file_get_contents("$file");
    $str = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $str);
    file_put_contents("$file", "$str");
    foreach ($_GET['species'] as $selectedOption)
        file_put_contents("../scripts/exclude_species_list.txt", $selectedOption . "\n", FILE_APPEND);
} elseif (isset($_GET['species']) && isset($_GET['del'])) {
    $file = '../scripts/exclude_species_list.txt';
    $str = file_get_contents("$file");
    $str = preg_replace('/^\h*\v+/m', '', $str);
    file_put_contents("$file", "$str");
    foreach ($_GET['species'] as $selectedOption) {
        $content = file_get_contents("../scripts/exclude_species_list.txt");
        $newcontent = str_replace($selectedOption, "", "$content");
        file_put_contents("../scripts/exclude_species_list.txt", "$newcontent");
    }
    $file = '../scripts/exclude_species_list.txt';
    $str = file_get_contents("$file");
    $str = preg_replace('/^\h*\v+/m', '', $str);
    file_put_contents("$file", "$str");
}
?>