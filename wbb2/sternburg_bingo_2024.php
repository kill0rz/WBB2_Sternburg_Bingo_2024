<?php

// Konfiguration Anfang

$erlaubtegruppen = array("1", "4");

// Konfiguration Ende

//
//

//
// Sternburg Bingo 2024 v1.0 by kill0rz
//

//
//

require './global.php';
require './acp/lib/config.inc.php';
require './acp/lib/class_parse.php';
require './acp/lib/options.inc.php';
$filename = "sternburg_bingo_2024.php";

$field = array();
$user_kronkorken = array();

$db = new db($sqlhost, $sqluser, $sqlpassword, $sqldb, $phpversion);

function inarray($array1, $array2) {
	foreach ($array1 as $a1) {
		foreach ($array2 as $a2) {
			if ($a1 == $a2) {
				return true;
			}
		}
	}
	return false;
}

function renewKronkorken() {
	global $user_kronkorken, $db, $userid_to_use;

	$user_kronkorken = array();
	$sql_query = "SELECT * FROM bb1_sternburg_bingo_2024_user WHERE userid='" . $userid_to_use . "' ORDER BY number;";
	$result = $db->unbuffered_query($sql_query);
	while ($row = mysqli_fetch_object($result)) {
		$user_kronkorken[$row->number] = $row->number;
	}
}

function gewinnText($nummer) {
	$wintext = '<h2><b>Herzlichen Gl&uuml;ckwunsch!</h2><br />BINGO!</b><br />Hier geht es jetzt f&uuml;r dich weiter: <a href="https://sternburg-bier.de/aktuelles/bingo.html" target="_blank">sternburg-bier.de</a> <br /> Drucke die Karte Nr. ' . $nummer . ' aus und schicke Sie ausgef&uuml;llt zusammen mit den Kronkorken an Sternburg.<br /><br />Entferne bitte die verschickten Kronkorken unten, um weiterhin den aktuellen Stand zu haben.';
	return $wintext;
}

$loggedin = false;
if ($wbbuserdata['userid'] != "0") {
	if (inarray($erlaubtegruppen, $wbbuserdata['groupids'])) {
		$loggedin = true;
	}
}

$error = "";

if ($loggedin) {
	//check is user is in DB
	$sql_query = "SELECT * FROM bb1_sternburg_bingo_2024_user_publicfields WHERE userid='" . $wbbuserdata['userid'] . "'";
	$result = $db->unbuffered_query($sql_query);
	if (mysqli_num_rows($result) == 0) {
		$sql_query = "INSERT INTO bb1_sternburg_bingo_2024_user_publicfields (`userid`, `public`) VALUES ('" . $wbbuserdata['userid'] . "', '0');";
		$result = $db->unbuffered_query($sql_query);
	}

	// Spielfelder freigeben
	$field_links = '<a href="sternburg_bingo_2024.php?usefield=own">Meine Spielfelder</a>  | ';
	$sql_query = "SELECT s.userid, username FROM bb1_sternburg_bingo_2024_user_publicfields s JOIN bb1_users u ON s.userid=u.userid WHERE s.public=1 AND s.userid <> '" . $wbbuserdata['userid'] . "'";
	$result = $db->unbuffered_query($sql_query);
	while ($row = mysqli_fetch_object($result)) {
		$field_links .= '<a href="sternburg_bingo_2024.php?usefield=' . $row->userid . '">Spielfelder von ' . $row->username . '</a> | ';
	}
	$field_links = substr($field_links, 0, -3);

	// toggle Freigabe
	if (isset($_GET['toggle_freigabe']) && intval($_GET['toggle_freigabe']) == 1) {
		$sql_query = "SELECT public FROM bb1_sternburg_bingo_2024_user_publicfields WHERE userid='" . $wbbuserdata['userid'] . "' LIMIT 1";
		$result = $db->unbuffered_query($sql_query);
		while ($row = mysqli_fetch_object($result)) {
			if ($row->public == 1) {
				$sql_query = "UPDATE bb1_sternburg_bingo_2024_user_publicfields SET public=0 WHERE userid='" . $wbbuserdata['userid'] . "'";
			} else {
				$sql_query = "UPDATE bb1_sternburg_bingo_2024_user_publicfields SET public=1 WHERE userid='" . $wbbuserdata['userid'] . "'";
			}
		}
		$db->unbuffered_query($sql_query);
	}

	$already_in_use = false;
	$is_someone_else = false;
	$canedit = false;

	if (isset($_GET['usefield']) && trim($_GET['usefield']) != '') {
		$sql_query = "SELECT username, s.userid, public FROM bb1_sternburg_bingo_2024_user_publicfields s JOIN bb1_users u ON s.userid=u.userid WHERE s.userid=" . intval($_GET['usefield']) . " LIMIT 1;";
		$result = $db->unbuffered_query($sql_query);
		while ($row = mysqli_fetch_object($result)) {
			if ($row->public == 1) {
				$already_in_use = true;

				// Spielfeld eines anderen Users
				$canedit = false;
				$userid_to_use = $row->userid;
				$username = $row->username;
				$is_someone_else = true;
			}
		}
	}

	if (!$already_in_use) {
		// Eigenes Spielfeld des Users
		$canedit = true;
		$userid_to_use = $wbbuserdata['userid'];
	}

	$sql_query = "SELECT public FROM bb1_sternburg_bingo_2024_user_publicfields WHERE userid='" . $wbbuserdata['userid'] . "' LIMIT 1";
	$result = $db->unbuffered_query($sql_query);
	while ($row = mysqli_fetch_object($result)) {
		if ($row->public == 1) {
			$my_field_public_is = "ist ";
		} else {
			$my_field_public_is = "ist nicht ";
		}
	}

	//find all Kronkorken, die der Nutzer schon hat
	renewKronkorken();

	// DEL-request?
	if ($canedit && isset($_GET['action']) && trim($_GET['action']) == "del" && isset($_GET['number']) && intval($_GET['number']) != '') {
		$number_to_del = intval(trim($_GET['number']));
		$sql_query = "DELETE FROM bb1_sternburg_bingo_2024_user WHERE number='" . $number_to_del . "' && userid='" . $userid_to_use . "'";
		$db->unbuffered_query($sql_query);
	} elseif ($canedit && isset($_POST['action']) && trim($_POST['action']) == "add" && isset($_POST['number']) && intval($_POST['number']) != '') {
		$number_to_add = intval(trim($_POST['number']));
		$sql_query = "INSERT INTO bb1_sternburg_bingo_2024_user (`userid`, `number`) VALUES ('" . $userid_to_use . "', '" . $number_to_add . "')";
		$db->unbuffered_query($sql_query);
	}

	//find all Kronkorken, die der Nutzer schon hat
	renewKronkorken();

	// Part 1: Spielfelder
	// suche alle Feldnummern
	$result = $db->unbuffered_query("SELECT * FROM bb1_sternburg_bingo_2024_fields GROUP BY field_nr ORDER BY field_nr ASC");
	while ($row = mysqli_fetch_object($result)) {
		$field_treffer_xy = array(array());
		$field_treffer_yx = array(array());

		// suche für jede Fieldnummer die zaheln und ordne sie an
		$result2 = $db->unbuffered_query("SELECT * FROM bb1_sternburg_bingo_2024_fields WHERE field_nr='" . $row->field_nr . "' ORDER BY pos_y ASC, pos_x ASC");
		$fields .= "<h2>Feld {$row->field_nr}:</h2><br />";
		while ($row2 = mysqli_fetch_object($result2)) {
			if ($row2->pos_x == 1) {
				$fields .= "<div class='fields_row'>";
			}

			$add_class = '';
			foreach ($user_kronkorken as $number) {
				if ($number == $row2->number) {
					$add_class = ' gotit';
					$field_treffer_xy[$row2->pos_x][$row2->pos_y] = true;
					$field_treffer_yx[$row2->pos_y][$row2->pos_x] = true;
					break;
				}
			}

			$fields .= "<div class='field" . $add_class . "'>" . str_pad($row2->number, 2, '0', STR_PAD_LEFT) . "</div>";
			if ($row2->pos_x == 5) {
				$fields .= "</div>";
			}
		}

		//Bingo?
		$field_has_win = false;

		// senkrecht
		foreach ($field_treffer_xy as $key => $value) {
			if (count($value) == 5 && !$field_has_win) {
				$fields .= gewinnText($row->field_nr);
				$field_has_win = true;
				break;
			}
		}

		// waagerecht
		foreach ($field_treffer_yx as $key => $value) {
			if (count($value) == 5 && !$field_has_win) {
				$fields .= gewinnText($row->field_nr);
				$field_has_win = true;
				break;
			}
		}

		// diagonal \
		$x = 1;
		$found = 0;
		while (!$field_has_win && $x < 6 && isset($field_treffer_xy[$x][$x])) {
			$x++;
			$found++;
		}
		if ($found == 5) {
			$fields .= gewinnText($row->field_nr);
			$field_has_win = true;
		}

		// diagonal /
		$x = 5;
		$y = 1;
		$found = 0;
		while (!$field_has_win && $x > 0 && $y < 6 && isset($field_treffer_xy[$x][$y])) {
			$x--;
			$y++;
			$found++;
		}
		if ($found == 5) {
			$fields .= gewinnText($row->field_nr);
			$field_has_win = true;
		}

	}

	// Part 2: Schon gesammelt
	$has_kronkorken = '';

	if ($canedit && count($user_kronkorken) > 0) {
		$klickhinweis = 'Klicken zum Entfernen';
	} else {
		$klickhinweis = '';
	}

	$counter = -1;
	foreach ($user_kronkorken as $key => $number) {
		if ($counter++ == 8) {
			$has_kronkorken .= "</div>";
			$has_kronkorken .= '<div class="sammlung_top">';
			$counter = 0;
		}

		if ($canedit) {
			$has_kronkorken .= '<div class="sammlung"><a href="./sternburg_bingo_2024.php?action=del&amp;number=' . $number . '"><img src="img/sternburg_bingo_2024/sternburg_deckel.png" alt="" /> <h1>' . str_pad($number, 2, '0', STR_PAD_LEFT) . '</h1></a></div>';
		} else {
			$has_kronkorken .= '<div class="sammlung"><img src="img/sternburg_bingo_2024/sternburg_deckel.png" alt="" /> <h1>' . str_pad($number, 2, '0', STR_PAD_LEFT) . '</h1></div>';
		}
	}

	// Part 3: Nummer hinzufuegen
	if ($canedit) {
		$number_options = '';
		for ($i = 1; $i < 100; $i++) {
			if (!isset($user_kronkorken[$i])) {
				$number_options .= "<option>{$i}</option>\n";
			}
		}
		$select_new_kronkorken = '<select name="number"> ' . $number_options . ' </select> <input type="hidden" name="action" value="add" /> <button type="submit">Kronkorken hinzugf&uuml;gen</button>';
	} else {
		$select_new_kronkorken = 'Du kannst nur Nummern zu deinen eigenen Spielfeldern hinzuf&uuml;gen';
	}

} else {
	echo "<meta http-equiv='refresh' content='0,index.php' />";
}

eval("\$tpl->output(\"" . $tpl->get("sternburg_bingo_2024") . "\");");
