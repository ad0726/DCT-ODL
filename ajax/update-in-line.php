<?php
$ROOT = "../";
include($ROOT.'conf/conf.php');

if ($_REQUEST['formfilled'] == 42) {
    echo '<tr id="updateInLine" class="line" style="display: none;">
        <td>
            <div>
                <label for="new_id">New position</label><br />
                <input type="number" class="pos" min="0" name="new_id" value="'.$_REQUEST['id'].'">
            </div>
            <input id="cover" type="file" class="file" name="cover">
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