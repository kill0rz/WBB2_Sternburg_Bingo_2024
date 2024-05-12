<?xml version="1.0" encoding="{$lang->items['LANG_GLOBAL_ENCODING']}"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="{$lang->items['LANG_GLOBAL_DIRECTION']}" lang="{$lang->items['LANG_GLOBAL_LANGCODE']}" xml:lang="{$lang->items['LANG_GLOBAL_LANGCODE']}">

<head>
	<title>$master_board_name | Sternburg Bingo 2024</title>
	$headinclude
	<style>
	.field {
		outline: 1px solid black;
		height: 60px;
		width: 70px;
		display: flex;
		justify-content: center;
		align-items: center;
		font-size: 40px;
		display: inline-block;
		background-color: white;
	}
	
	.gotit {
		background-color: green;
	}
	
	.sammlung {
		position: relative;
	}
	
	.sammlung_top {
		display: flex;
		justify-content: center;
		align-items: center;
	}
	
	div.sammlung h1 {
		position: absolute;
		top: 32px;
		left: 1px;
		width: 100%;
	}
	</style>
</head>

<body>
	$header
	<table cellpadding="{$style['tableincellpadding']}" cellspacing="{$style['tableincellspacing']}" border="{$style['tableinborder']}" style="width:{$style['tableinwidth']}" class="tableinborder">
		<tr>
			<td class="tablea">
				<table cellpadding="0" cellspacing="0" border="0" style="width:100%">
					<tr class="tablea_fc">
						<td align="left">
							<span class="smallfont">
								<b>
									<a href="index.php{$SID_ARG_1ST}">$master_board_name</a> &raquo; Sternburg Bingo 2024 v1
								</b>
							</span>
						</td>
						<td align="right"><span class="smallfont"><b>$usercbar</b></span></td>
					</tr>
				</table>
			</td>
		</tr>
		<br />
		<tr>
			<td align="left">
				<table cellpadding="4" cellspacing="1" border="0" style="width:100%" class="tableinborder">
					<tr>
						<td align="left" colspan="4" nowrap="nowrap" class="tabletitle">
							<span class="normalfont">
								<b>Spielfelder freigeben</b>
							</span>
						</td>
					</tr>
					<tr align="left">
						<td colspan="2" class="tablea" align="center">
							<span class="smallfont">
								Bitte w&auml;hle ein Spiel: $field_links <br /><br />
								Mein Spielfeld <b>$my_field_public_is</b> freigeben.<br />
								<a href="sternburg_bingo_2024.php?toggle_freigabe=1">&auml;ndern</a>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="left">
				<table cellpadding="4" cellspacing="1" border="0" style="width:100%" class="tableinborder">
					<tr>
						<td align="left" colspan="4" nowrap="nowrap" class="tabletitle">
							<span class="normalfont">
								<b>Spielfelder</b>
							</span>
						</td>
					</tr>
					<tr align="left">
						<td colspan="2" class="tablea" align="center">
							<span class="smallfont">
								<div class="fields">
									$fields
								</div>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="left">
				<table cellpadding="4" cellspacing="1" border="0" style="width:100%" class="tableinborder">
					<tr>
						<td align="left" colspan="4" nowrap="nowrap" class="tabletitle">
							<span class="normalfont">
								<b>Schon gesammelt</b>
							</span>
						</td>
					</tr>
					<tr align="left">
						<td colspan="2" class="tablea" align="center">
							<span class="smallfont">
								$klickhinweis <br /><br />
								<div class="sammlung_top">
									$has_kronkorken
								</div>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td align="left">
				<table cellpadding="4" cellspacing="1" border="0" style="width:100%" class="tableinborder">
					<tr>
						<td align="left" colspan="4" nowrap="nowrap" class="tabletitle">
							<span class="normalfont">
								<b>Nummer hinzuf&uuml;gen</b>
							</span>
						</td>
					</tr>
					<tr align="left">
						<td colspan="2" class="tablea" align="center">
							<span class="smallfont">
								<form action="./sternburg_bingo_2024.php" method="post">
									$select_new_kronkorken
								</form>
							</span>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	$footer
