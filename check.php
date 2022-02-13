<?php
header("Content-Type: application/json");

$files = array();

function addDir($dir = "")
{
    global $files;
    foreach (scandir("files/$dir") as $file) {
        if ($file === "." || $file === "..")
            continue;
        if (!is_dir("files/$dir$file"))
            array_push($files, array("path" =>  "$dir$file", "size" => filesize("files/$dir$file"), "md5" => md5_file("files/$dir$file")));
        else
            addDir("$dir$file/");
    }
}

addDir();

$ignore = array_filter(preg_split("/((\r?\n)|(\r\n?))/", file_get_contents("ignorelist.txt")));

echo json_encode(
    array(
        "files" => $files,
        "ignore" => $ignore
    )
);
