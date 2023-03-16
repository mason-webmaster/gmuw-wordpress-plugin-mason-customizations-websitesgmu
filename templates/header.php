<?php

/**
 * Custom template header file
 */

get_header();

?>

<main id="site-content" role="main">

<?php 
// Is the current request for a taxonomy object?
if(is_tax()){
	require('header-taxonomy.php');
}
?>

<article>

	<div class="post-inner">

		<div class="gmuj-sidebar-wrapper">
			<div class="gmuj-sidebar"></div>
			<div class="entry-content">
