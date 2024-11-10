<?php /* Smarty version 2.6.16, created on 2020-08-10 21:50:16
         compiled from index.html */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'linker', 'index.html', 105, false),)), $this); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="<?php echo $this->_tpl_vars['LANG']; ?>
">
<head>
  <title>Horst Kaechele - <?php echo $this->_tpl_vars['TITLE']; ?>
</title>
  <?php echo $this->_tpl_vars['HEAD']; ?>

  <script type="text/javascript" src="_templates/_script/main.js"></script>
  <link rel="stylesheet" href="_css/css.css" type="text/css" />
  <link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />

  	<!--[if IE 7]>
	<style type="text/css">@import url(http://horstkaechele.de/plib/_templates/_css/plib_IE7.css);</style>
	<![endif]-->
	<!--[if lte IE 6]>
	<style type="text/css">@import url(http://horstkaechele.de/plib/_templates/_css/plib_IE6.css);</style>
	<![endif]-->

</head>

<body>


<div class="container1">
<a name="top" id="top"> </a>


<p class="navpos">

<a href="../index.html" class="hl">Home</a>

<a href="../plib/" class="hl">HK's Files</a>

<a href="../fotagen_pics/phpslideshow.php" class="hl">Nonverbales</a>

<a href="../pg/cv.html" class="hl">HK's CV</a>

<!-- 
<a href="../../gb2008work/gb.php" class="hl">Guestbook</a>

 -->
<a href="../pg/impressum.html" class="hl">Impressum</a>

<a href="_admin/_admin.php?id=<?php echo $this->_tpl_vars['ID']; ?>
" class="hl">Login</a>

</p>

<p class="Ueberschrift" >HK's Files <a href="suche.php" class="hl index" style="margin-left:18px;">Index Search</a><b style="font-size:10px; float:right;margin-top:-22px;margin-right:5px;">PLib v.03</b></p>

<div class="Navigation">
  <?php $_from = $this->_tpl_vars['NAV']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['nav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['nav']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['n'] => $this->_tpl_vars['nav']):
        $this->_foreach['nav']['iteration']++;
?>
    <?php if ($this->_tpl_vars['nav']['active'] == 1): ?>
    <?php $this->assign('akt_nav', $this->_tpl_vars['nav']['id']); ?>
    <?php $this->assign('nav_arr', $this->_tpl_vars['n']); ?>
    <span><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['nav']['id']; ?>
_0_0" class="active"><?php echo $this->_tpl_vars['nav']['name']; ?>
</a></span>
    <?php else: ?>
    <span><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['nav']['id']; ?>
_0_0"><?php echo $this->_tpl_vars['nav']['name']; ?>
</a></span>
    <?php endif; ?>
  <?php endforeach; endif; unset($_from); ?>
</div>

</div>


<div class="headbg">
</div>

<div class="clear"></div>
<div class="container2">
  <?php if ($this->_tpl_vars['SUBNAV']['0']['id'] > 0): ?>
	<?php $_from = $this->_tpl_vars['SUBNAV']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subnav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subnav']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sn'] => $this->_tpl_vars['subnav']):
        $this->_foreach['subnav']['iteration']++;
?>
		<?php if ($this->_tpl_vars['subnav']['active'] == 1): ?>
			<?php $this->assign('akt_subnav', $this->_tpl_vars['subnav']['id']); ?>
			<?php $this->assign('subnav_arr', $this->_tpl_vars['sn']); ?>
			<?php $this->assign('akt_topic', $this->_tpl_vars['subnav']['name']); ?>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
  <?php endif; ?>


	<?php if ($this->_tpl_vars['TOPIC']['0']['id'] != ''): ?>
		<?php $_from = $this->_tpl_vars['TOPIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['topic'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['topic']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['topic']):
        $this->_foreach['topic']['iteration']++;
?>
			<?php if ($this->_tpl_vars['topic']['active'] == 1): ?>
			  <?php $this->assign('akt_topic', $this->_tpl_vars['topic']['name']); ?>
			  <?php $this->assign('akt_topictext', $this->_tpl_vars['topic']['text']); ?>
			  <?php $this->assign('akt_topicid', $this->_tpl_vars['topic']['id']); ?>
			  <?php else: ?>
			<?php endif; ?>
		<?php endforeach; endif; unset($_from); ?>
	<?php endif; ?>


	<?php if ($this->_tpl_vars['NAV'][$this->_tpl_vars['nav_arr']]['intro_text'] == ''): ?>
	<?php elseif (isset ( $this->_tpl_vars['NAV'][$this->_tpl_vars['nav_arr']]['intro_text'] ) && ! $this->_tpl_vars['SUBNAV'][$this->_tpl_vars['subnav_arr']]['text'] && ! $this->_tpl_vars['akt_topictext']): ?>
   	<?php elseif (isset ( $this->_tpl_vars['SUBNAV'][$this->_tpl_vars['subnav_arr']]['text'] ) && ! $this->_tpl_vars['akt_topictext']): ?>
    <?php elseif (isset ( $this->_tpl_vars['akt_topictext'] )): ?>
    <?php endif; ?>
</div>

<div class="container2">

	<?php if ($this->_tpl_vars['NAV'][$this->_tpl_vars['nav_arr']]['intro_text'] == ''): ?>
	<div class="introtext2">.&nbsp;.&nbsp;.&nbsp;</div>
	<div class="introtext2abschluss"></div>
	<?php elseif (isset ( $this->_tpl_vars['NAV'][$this->_tpl_vars['nav_arr']]['intro_text'] ) && ! $this->_tpl_vars['SUBNAV'][$this->_tpl_vars['subnav_arr']]['text'] && ! $this->_tpl_vars['akt_topictext']): ?>
	<div class="sprachindikator"></div>
	<div class="introtext2"><?php echo ((is_array($_tmp=$this->_tpl_vars['NAV'][$this->_tpl_vars['nav_arr']]['intro_text'])) ? $this->_run_mod_handler('linker', true, $_tmp) : smarty_modifier_linker($_tmp)); ?>
</div>
	<div class="introtext2abschluss"></div>

   	<?php elseif (isset ( $this->_tpl_vars['SUBNAV'][$this->_tpl_vars['subnav_arr']]['text'] ) && ! $this->_tpl_vars['akt_topictext']): ?>
    <div class="introtext2"><?php echo ((is_array($_tmp=$this->_tpl_vars['SUBNAV'][$this->_tpl_vars['subnav_arr']]['text'])) ? $this->_run_mod_handler('linker', true, $_tmp) : smarty_modifier_linker($_tmp)); ?>
</div>
	<div class="ordnerindikator"></div>

    <?php elseif (isset ( $this->_tpl_vars['akt_topictext'] )): ?>
    <div class="introtext2">
    <?php echo ((is_array($_tmp=$this->_tpl_vars['akt_topictext'])) ? $this->_run_mod_handler('linker', true, $_tmp) : smarty_modifier_linker($_tmp)); ?>
</div>
    <div class="themenindikator"></div>
    <?php endif; ?>



	<div class="spalte">
<!--  -->
  <?php if ($this->_tpl_vars['SUBNAV']['0']['id'] > 0): ?>
  <div class="spaltenheadline"><img src="_templates/_img/ordner_icon.gif" alt="" /></div>
  	<ol>
	<?php $_from = $this->_tpl_vars['SUBNAV']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['subnav'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['subnav']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['sn'] => $this->_tpl_vars['subnav']):
        $this->_foreach['subnav']['iteration']++;
?>
		<?php if ($this->_tpl_vars['subnav']['active'] == 1): ?>
			<?php $this->assign('akt_subnav', $this->_tpl_vars['subnav']['id']); ?>
			<?php $this->assign('subnav_arr', $this->_tpl_vars['sn']); ?>
			<?php $this->assign('akt_topic', $this->_tpl_vars['subnav']['name']); ?>
			<!-- <?php $this->assign('leadingNum', ($this->_tpl_vars['leaingNum'])."@iteration"); ?> -->
			<li class="print"><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['akt_nav']; ?>
_<?php echo $this->_tpl_vars['subnav']['id']; ?>
_0" class="standardlink"><!-- <?php echo $this->_tpl_vars['sn']; ?>
 --> <?php echo $this->_tpl_vars['subnav']['name']; ?>
</a></li>
			<?php else: ?>
			<li class="noprint"><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['akt_nav']; ?>
_<?php echo $this->_tpl_vars['subnav']['id']; ?>
_0" class="grey"><!-- <?php echo $this->_tpl_vars['sn']; ?>
 --> <?php echo $this->_tpl_vars['subnav']['name']; ?>
</a></li>
		<?php endif; ?>
	<?php endforeach; endif; unset($_from); ?>
	</ol>
  <?php endif; ?>
  </div>

	<?php if ($this->_tpl_vars['TOPIC']['0']['id'] == ''): ?>
	<div class="spalte extras invisible">&nbsp;</div>
	<?php else: ?>
	<div class="spalte extras">
	<div class="spaltenheadline"><img src="_templates/_img/themen_icon.gif" alt="" /></div>
	  <?php if ($this->_tpl_vars['TOPIC']['0']['id'] != ''): ?>
		<ol>
		  <?php $_from = $this->_tpl_vars['TOPIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['topic'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['topic']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['topic']):
        $this->_foreach['topic']['iteration']++;
?>
				<?php if ($this->_tpl_vars['topic']['active'] == 1): ?>
				  <?php $this->assign('akt_topic', $this->_tpl_vars['topic']['name']); ?>
				  <?php $this->assign('akt_topictext', $this->_tpl_vars['topic']['text']); ?>
				  <?php $this->assign('akt_topicid', $this->_tpl_vars['topic']['id']); ?>
				  <li class="print"><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['akt_nav']; ?>
_<?php echo $this->_tpl_vars['akt_subnav']; ?>
_<?php echo $this->_tpl_vars['topic']['id']; ?>
" class="standardlink"><?php echo $this->_tpl_vars['topic']['name']; ?>
</a></li>
				  <?php else: ?>
				  <li class="noprint"><a href="<?php echo $this->_tpl_vars['smarty_self']; ?>
?id=<?php echo $this->_tpl_vars['akt_nav']; ?>
_<?php echo $this->_tpl_vars['akt_subnav']; ?>
_<?php echo $this->_tpl_vars['topic']['id']; ?>
" class="grey"><?php echo $this->_tpl_vars['topic']['name']; ?>
</a></li>
				<?php endif; ?>
			<?php endforeach; endif; unset($_from); ?>
		</ol>
		<?php endif; ?>
	  </div>
	<?php endif; ?>

    <div class="spaltedia">
    <?php if (isset ( $this->_tpl_vars['CONTENT']['0']['file'] ) && $this->_tpl_vars['akt_subnav'] > 0): ?>
   <div class="spaltenheadline ieplus"><img src="_templates/_img/dateien_icon.gif" alt="" /></div>
		<p class="titel3">Message + Media</p>
		  <?php $_from = $this->_tpl_vars['CONTENT']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['f'] => $this->_tpl_vars['text']):
?>
			<div class="pdfBox" id="id<?php echo $this->_tpl_vars['text']['id']; ?>
"><h2 id="d<?php echo $this->_tpl_vars['f']; ?>
" class="titel"><?php echo $this->_tpl_vars['text']['headline']; ?>
</h2>
			<p class="pdfdescription"><?php echo ((is_array($_tmp=$this->_tpl_vars['text']['content'])) ? $this->_run_mod_handler('linker', true, $_tmp) : smarty_modifier_linker($_tmp)); ?>
</p>

			<?php if ($this->_tpl_vars['text']['image_position'] == 'short' && $this->_tpl_vars['text']['image'] != ''): ?>
			<div class="customimage">
			<img src="_uploads/_img/<?php echo $this->_tpl_vars['text']['image']; ?>
" alt="<?php echo $this->_tpl_vars['text']['headline']; ?>
" class="artimg" />
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['text']['extratext'] != '' || ( $this->_tpl_vars['text']['image_position'] == 'long' && $this->_tpl_vars['text']['image'] != '' )): ?><a name="<?php echo $this->_tpl_vars['f']; ?>
"></a><div id="short<?php echo $this->_tpl_vars['f']; ?>
" class="show"><a href="#<?php echo $this->_tpl_vars['f']; ?>
" onclick="SwapLayer('long<?php echo $this->_tpl_vars['f']; ?>
','short<?php echo $this->_tpl_vars['f']; ?>
')">read more...</a></div>
			<div id="long<?php echo $this->_tpl_vars['f']; ?>
" class="hide">
				<?php if ($this->_tpl_vars['text']['image_position'] == 'long' && $this->_tpl_vars['text']['image'] != ''): ?>
					<div class="customimage">
					<img src="_uploads/_img/<?php echo $this->_tpl_vars['text']['image']; ?>
" alt="<?php echo $this->_tpl_vars['text']['headline']; ?>
" class="artimg" />
					</div>
				<?php endif; ?>
			<div class="longtext">
				<h1 class="longtextheadline"><?php echo $this->_tpl_vars['text']['extrahead']; ?>
</h1>
				<?php echo $this->_tpl_vars['text']['extratext']; ?>

			</div>
			<a href="#<?php echo $this->_tpl_vars['f']; ?>
" onclick="SwapLayer('short<?php echo $this->_tpl_vars['f']; ?>
','long<?php echo $this->_tpl_vars['f']; ?>
')">close</a>
			</div><?php endif; ?>
			<?php if ($this->_tpl_vars['text']['file'] != ''): ?>
				<!-- (<?php echo $this->_tpl_vars['text']['date']; ?>
) -->
				<ul class="download">
			 		<li><a href="_uploads/_pdfs/<?php echo $this->_tpl_vars['text']['file']; ?>
"><img src="_templates/_img/pdf.gif" alt="" /> download: <?php echo $this->_tpl_vars['text']['file']; ?>
 </a></li>
				</ul>
			<?php else: ?>
			 	<br />
			<?php endif; ?>
			 <ul class="tagul">
				<?php if ($this->_tpl_vars['text']['tag'] != ''): ?>
					<li>Stichworte:</li>
						<?php $_from = $this->_tpl_vars['text']['tag']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['t'] => $this->_tpl_vars['tags']):
        $this->_foreach['tag']['iteration']++;
?>
							<?php if (($this->_foreach['tag']['iteration'] == $this->_foreach['tag']['total']) == TRUE): ?>
								<li><a href="suche.php?tagid=<?php echo $this->_tpl_vars['tags']['id']; ?>
&amp;tname=<?php echo $this->_tpl_vars['tags']['tagname']; ?>
"><?php echo $this->_tpl_vars['tags']['tagname']; ?>
</a></li>
								<?php else: ?>
								<li><a href="suche.php?tagid=<?php echo $this->_tpl_vars['tags']['id']; ?>
&amp;tname=<?php echo $this->_tpl_vars['tags']['tagname']; ?>
"><?php echo $this->_tpl_vars['tags']['tagname']; ?>
</a>,</li>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
						<li>&nbsp;</li>
				<?php endif; ?>
			</ul>
            <ul class="nameul">
				<?php if ($this->_tpl_vars['text']['names'] != ''): ?>
					<li>Namen:</li>
						<?php $_from = $this->_tpl_vars['text']['names']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['t'] => $this->_tpl_vars['tagnames']):
        $this->_foreach['name']['iteration']++;
?>
							<?php if (($this->_foreach['name']['iteration'] == $this->_foreach['name']['total']) == TRUE): ?>
								<li><a href="suche.php?nameid=<?php echo $this->_tpl_vars['tagnames']['id']; ?>
&amp;nname=<?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
"><?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
</a></li>
								<?php else: ?>
								<li><a href="suche.php?nameid=<?php echo $this->_tpl_vars['tagnames']['id']; ?>
&amp;nname=<?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
"><?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
</a>,</li>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
						<li>&nbsp;</li>
				<?php endif; ?>
			</ul>
		  </div>
		  <?php endforeach; endif; unset($_from); ?>
	<?php elseif (isset ( $this->_tpl_vars['TOPIC']['direkt']['headline'] )): ?>
	<div class="spaltenheadline ieplus"><img src="_templates/_img/dateien_icon.gif" alt="" /></div>
	<div><div class="titel3">PDFs</div></div>
	<?php $_from = $this->_tpl_vars['TOPIC']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }if (count($_from)):
    foreach ($_from as $this->_tpl_vars['direkt']):
?>
	<div id="id<?php echo $this->_tpl_vars['text']['id']; ?>
"><h2 id="d<?php echo $this->_tpl_vars['f']; ?>
" class="titel"><?php echo $this->_tpl_vars['text']['headline']; ?>
</h2>
			<p class="pdfdescription"><?php echo $this->_tpl_vars['direkt']['content']; ?>
</p>
			<?php if ($this->_tpl_vars['direkt']['image_position'] == 'short' && $this->_tpl_vars['direkt']['image'] != ''): ?>
			<div class="customimage">
			<img src="_uploads/_img/<?php echo $this->_tpl_vars['direkt']['image']; ?>
" alt="<?php echo $this->_tpl_vars['direkt']['headline']; ?>
" class="artimg" />
			</div>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['direkt']['extratext'] != '' || ( $this->_tpl_vars['text']['image_position'] == 'long' && $this->_tpl_vars['text']['image'] != '' )): ?><div id="short<?php echo $this->_tpl_vars['f']; ?>
" class="show"><a href="#<?php echo $this->_tpl_vars['f']; ?>
" onclick="SwapLayer('long<?php echo $this->_tpl_vars['f']; ?>
','short<?php echo $this->_tpl_vars['f']; ?>
')">read more...</a></div>
			<div id="long<?php echo $this->_tpl_vars['f']; ?>
" class="hide">
				<?php if ($this->_tpl_vars['direkt']['image_position'] == 'long' && $this->_tpl_vars['direkt']['image'] != ''): ?>
					<div class="customimage">
					<img src="_uploads/_img/<?php echo $this->_tpl_vars['direkt']['image']; ?>
" alt="<?php echo $this->_tpl_vars['direkt']['headline']; ?>
" class="artimg" />
					</div>
				<?php endif; ?>
			<div class="longtext">
				<h1 class="longtextheadline"><?php echo $this->_tpl_vars['direkt']['extrahead']; ?>
</h1>
				<?php echo $this->_tpl_vars['direkt']['extratext']; ?>

			</div>
			<a href="#<?php echo $this->_tpl_vars['f']; ?>
" onclick="SwapLayer('short<?php echo $this->_tpl_vars['f']; ?>
','long<?php echo $this->_tpl_vars['f']; ?>
')">close</a>
			</div><?php endif; ?>
			<?php if ($this->_tpl_vars['direkt']['file'] != ''): ?>
				<!-- (<?php echo $this->_tpl_vars['direkt']['date']; ?>
) -->
				<ul class="download">
			 		<li><a href="_uploads/_pdfs/<?php echo $this->_tpl_vars['direkt']['file']; ?>
"><img src="_templates/_img/pdf.gif" alt="" /> download: <?php echo $this->_tpl_vars['direkt']['file']; ?>
 </a></li>
				</ul>
			<?php else: ?>
			 	<br />
			<?php endif; ?>
			 <ul class="tagul">
				<?php if ($this->_tpl_vars['text']['tag'] != ''): ?>
					<li>Stichworte:</li>
						<?php $_from = $this->_tpl_vars['text']['tag']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['tag'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['tag']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['t'] => $this->_tpl_vars['tags']):
        $this->_foreach['tag']['iteration']++;
?>
							<?php if (($this->_foreach['tag']['iteration'] == $this->_foreach['tag']['total']) == TRUE): ?>
								<li><a href="suche.php?tagid=<?php echo $this->_tpl_vars['tags']['id']; ?>
&amp;tname=<?php echo $this->_tpl_vars['tags']['tagname']; ?>
"><?php echo $this->_tpl_vars['tags']['tagname']; ?>
</a></li>
								<?php else: ?>
								<li><a href="suche.php?tagid=<?php echo $this->_tpl_vars['tags']['id']; ?>
&amp;tname=<?php echo $this->_tpl_vars['tags']['tagname']; ?>
"><?php echo $this->_tpl_vars['tags']['tagname']; ?>
</a>,</li>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
						<li>&nbsp;</li>
				<?php endif; ?>
			</ul>
            <ul class="nameul">
				<?php if ($this->_tpl_vars['text']['names'] != ''): ?>
					<li>Namen:</li>
						<?php $_from = $this->_tpl_vars['text']['names']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['name'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['name']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['t'] => $this->_tpl_vars['tagnames']):
        $this->_foreach['name']['iteration']++;
?>
							<?php if (($this->_foreach['name']['iteration'] == $this->_foreach['name']['total']) == TRUE): ?>
								<li><a href="suche.php?nameid=<?php echo $this->_tpl_vars['tagnames']['id']; ?>
&amp;nname=<?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
"><?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
</a></li>
								<?php else: ?>
								<li><a href="suche.php?nameid=<?php echo $this->_tpl_vars['tagnames']['id']; ?>
&amp;nname=<?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
"><?php echo $this->_tpl_vars['tagnames']['tagname']; ?>
</a>,</li>
							<?php endif; ?>
						<?php endforeach; endif; unset($_from); ?>
						<?php else: ?>
						<li>&nbsp;</li>
				<?php endif; ?>
			</ul>
		  </div>
	<?php endforeach; endif; unset($_from); ?>
    <?php elseif (isset ( $this->_tpl_vars['CONTENT']['0']['error'] ) && $this->_tpl_vars['akt_topicid'] > 0): ?>
      <div class="spaltenheadline"><img src="_templates/_img/dateien_icon.gif" alt="" /></div>
      	  <?php if (isset ( $this->_tpl_vars['akt_topic'] )): ?><p><div class="titel3"><?php echo $this->_tpl_vars['akt_topic']; ?>
</div></p><?php endif; ?>
          <h2 class="titel"><?php echo $this->_tpl_vars['CONTENT']['0']['error']; ?>

          <div><?php echo $this->_tpl_vars['CONTENT']['0']['errortext']; ?>
<br/></div></h2>
    <?php endif; ?>
	</div>
</div>
<div class="indicatorsolo" style="background:url(_templates/_img/indicatorshupsolo.png) repeat-x 0px 0px;">
</div>


<div class="container3 clear">
<p><a href="#top" class="standardlink">top</a></p>
<p><br>
Entry-ID : <?php echo $this->_tpl_vars['akt_nav']; ?>
_<?php if ($this->_tpl_vars['akt_subnav'] < 1): ?>0<?php else:  echo $this->_tpl_vars['akt_subnav'];  endif; ?>_<?php if ($this->_tpl_vars['akt_topicid'] < 1): ?>0<?php else:  echo $this->_tpl_vars['akt_topicid'];  endif; ?></p>
<!-- <?php echo $this->_tpl_vars['leadingNum']; ?>
 -->
</div>



<div id="footer"><p class="left">hks ulm-cd 2008</p><p class="right">PLib + visual concept + design <a href="http://www.jcc-hamburg.com" target="_blank">JCC-hamburg 2008</a></p></div>


</body>
</html>