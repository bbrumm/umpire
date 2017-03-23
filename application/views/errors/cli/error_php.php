<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div style="border:1px solid #990000; padding-left:20px; margin:0 0 10px 0; background:#ffe6e6; font-family: Arial, Helvetica, sans-serif;">

<h4>A PHP Error was encountered</h4>

<p><b>Severity:</b>    <?php echo $severity, "\n"; ?></p>
<p><b>Message:</b>     <?php echo $message, "\n"; ?></p>
<p><b>Filename:</b>    <?php echo $filepath, "\n"; ?></p>
<p><b>Line Number:</b> <?php echo $line; ?></p>

<?php if (defined('SHOW_DEBUG_BACKTRACE') && SHOW_DEBUG_BACKTRACE === TRUE): ?>

<p><b>Backtrace:</b></p>
<?php	foreach (debug_backtrace() as $error): ?>
<?php		if (isset($error['file']) && strpos($error['file'], realpath(BASEPATH)) !== 0): ?>
	File: <?php echo $error['file'], "\n"; ?>
	Line: <?php echo $error['line'], "\n"; ?>
	Function: <?php echo $error['function'], "\n\n"; ?>
<?php		endif ?>
<?php	endforeach ?>

<?php endif ?>
</div>