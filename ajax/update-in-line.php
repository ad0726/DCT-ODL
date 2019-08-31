<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if ($_REQUEST['formfilled'] == 42) {
    $role = whichRole();
    if ($role == "editor") {
        $display = "style='display: none;'";
    } else {
        $display = "";
    }
    echo '<tr id="updateInLine" class="line" style="display: none;">
        <td>
            <input type="hidden" name="id_arc" value="'.$_REQUEST['id'].'">
            <input type="hidden" name="position" value="'.$_REQUEST['position'].'">
            <div '.$display.'>
                <label for="new_id">Position</label><br />
                <input type="number" class="pos" min="0" name="new_id" value="'.$_REQUEST['position'].'">
            </div>
            <div>
                <label for="new_id">Cover</label><br />
                <input id="fake-input" type="button" value="Select a file">
                <input id="cover" type="file" accept="image/*" class="file" name="cover">
                <p id="result-file-selected"></p>
            </div>
            <div>
                <label>Title</label><br />
                <input type="text" class="input" name="title" value="'.$_REQUEST['title'].'">
            </div>
            <div>
                <label>Content</label><br />
                <textarea type="text" class="input" name="content">'.$_REQUEST['content'].'</textarea>
            </div>
            <div>
                <label>Urban</label><br />
                <input type="text" class="input" name="urban" value="'.$_REQUEST['urban'].'">
            </div>
            <div>
                <label>DCTrad</label><br />
                <input type="text" class="input" name="dctrad" value="'.$_REQUEST['dctrad'].'">
            </div>
            <div>
                <label>Event</label><br />
                <input type="checkbox" name="isEvent" id="checkboxIsEvent" '.$_REQUEST['isEvent'].'>
            </div>
            <input type="submit" class="btn_send" value="Send">
            <i class="far fa-times-circle btn_close" id="update_close"></i>
        </td>
    </tr>';
}