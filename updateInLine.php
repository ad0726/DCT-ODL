<?php
session_start();
include('function.php');

if ($_REQUEST['formfilled'] == 42) {
    echo '<tr id="updateInLine" class="line" style="display: none;">
        <td>
            <div>
                <label for="new_id">New position</label><br />
                <input type="number" class="pos" min="0" name="new_id">
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
                <input type="text" class="input" name="dctrad" value="'.$_REQUEST['dctrad'].'">
            </div>
            <div>
                <label>DCTrad</label><br />
                <input type="text" class="input" name="urban" value="'.$_REQUEST['urban'].'">
            </div>
            <div>
                <label>Event</label><br />
                <input type="checkbox" name="isEvent" id="checkboxIsEvent" '.$_REQUEST['isEvent'].'>
            </div>
            <input type="button" class="btn_send" value="Send">
        </td>
    </tr>';
}