<?php
/*
Plugin Name: Neon Text
Description: Neon effect for your text
Author: ERALION.com
Author URI: https://www.ERALION.com
Text Domain: neontext
Domain Path: /languages
Version: 1.3
*/
add_action( 'plugins_loaded', 'neontext_init' );
function neontext_init()
{
    load_plugin_textdomain( 'neontext', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}
function neontext_add_scripts(){	
	wp_enqueue_style( 'neontext-css', plugins_url( 'css/app.css', __FILE__ ), '', '1.0' );
	
	wp_register_script( 'jsnovacancy', plugins_url( 'js/jquery.novacancy.min.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jsnovacancy' );
	wp_register_script( 'jsnovacancy-app', plugins_url( 'js/app.js', __FILE__ ), array( 'jquery' ), '1.0', true );
	wp_enqueue_script( 'jsnovacancy-app' );
	
}
add_action( 'wp_enqueue_scripts', 'neontext_add_scripts' );

function neontext_shortcode( $atts, $content = "" ) {
	global $nbneontext;
	
	$nbneontext++;
	
	$atts = shortcode_atts( array(
		'color' => '',
		'reblinkprobability' => '',
		'blinkmin' => '',
		'blinkmax' => '',
		'loopmin' => '',
		'loopmax' => '',
		'glow' => '',
		'off' => '',
	), $atts, 'neontext' );
	
	$content_traite='';
	$content_letters = str_split($content);
	foreach ($content_letters as $char) {
		$content_traite.='<data class="novacancy on">'.$char.'</data>';
	}	
	
	$atts['color'] = filter_var($atts['color'], FILTER_SANITIZE_STRING);
	$atts['reblinkprobability'] = filter_var($atts['reblinkprobability'], FILTER_SANITIZE_STRING);
	$atts['blinkmin'] = filter_var($atts['blinkmin'], FILTER_SANITIZE_STRING);
	$atts['blinkmax'] = filter_var($atts['blinkmax'], FILTER_SANITIZE_STRING);
	$atts['loopmin'] = filter_var($atts['loopmin'], FILTER_SANITIZE_STRING);
	$atts['loopmax'] = filter_var($atts['loopmax'], FILTER_SANITIZE_STRING);
	$atts['glow'] = filter_var($atts['glow'], FILTER_SANITIZE_STRING);
	$atts['off'] = filter_var($atts['off'], FILTER_SANITIZE_STRING);

	$output='<span id="nbneontext_'.$nbneontext.'" novacancy-id="'.$nbneontext.'" class="nbneontext" data-color="'.$atts['color'].'" data-reblinkProbability="'.$atts['reblinkprobability'].'" data-blinkMin="'.$atts['blinkmin'].'" data-blinkMax="'.$atts['blinkmax'].'" data-loopMin="'.$atts['loopmin'].'" data-loopMax="'.$atts['loopmax'].'" data-glow="'.$atts['glow'].'" data-off="'.$atts['off'].'">'.$content_traite.'</span>';
	
	return $output;
}
add_shortcode( 'neontext', 'neontext_shortcode' );

function neontext_box_shortcode( $atts, $content = "" ) {
	return '<div class="board_wrap"><div class="board"><h1>'.do_shortcode($content).'</h1></div></div>';
}
add_shortcode( 'neontext_box', 'neontext_box_shortcode' );

function neontext_menu() {
        add_menu_page('Neon Text', 'Neon Text', 8, 'neontext_panel','neontext_panel', 'dashicons-editor-textcolor');
}
add_action("admin_menu", "neontext_menu");
function neontext_panel() {	
	$reblinkprobability_options='';
	for($i=0;$i<1;$i=$i+0.1) {
		$reblinkprobability_options.='<option value="'.$i.'">'.$i.'</option>';
	}
	$blink_options='';
	for($i=0;$i<1;$i=$i+0.1) {
		$blink_options.='<option value="'.$i.'">'.$i.'</option>';
	}
	$loop_options='';
	for($i=0;$i<10;$i=$i+0.1) {
		$loop_options.='<option value="'.$i.'">'.$i.'</option>';
	}
	$num_options='';
	for($i=0;$i<10;$i=$i+1) {
		$num_options.='<option value="'.$i.'">'.$i.'</option>';
	}

	echo '
	<div class="wrap">
		<h2>Neon Text - '.__( 'Shortcode generator' , 'neontext' ).'</h2>
		<div>
			<p>'.__( 'You can use this generator to get the shortcode that you need to customize your neon texts.' , 'neontext' ).'</p>
			<h3>'.__( 'Generate shortcode' , 'neontext' ).'</h3>
			<table>
			';
			for ($i=1;$i<=5;$i++) {
				echo '
				<tr id="line'.$i.'">
					<td>'.__( 'Line' , 'neontext' ).' #'.$i.' :</td>
					<td><input type="text" style="width:200px"></td>
					<td>color: <input type="text" style="width:100px"></td>
					<td>reblinkprobability: <select style="width:100px"><option value="">-</option>'.$reblinkprobability_options.'</select></td>
					<td>blinkmin: <select style="width:100px"><option value="">-</option>'.$blink_options.'</select></td>
					<td>blinkmax: <select style="width:100px"><option value="">-</option>'.$blink_options.'</select></td>
					<td>loopmin: <select style="width:100px"><option value="">-</option>'.$loop_options.'</select></td>
					<td>loopmax: <select style="width:100px"><option value="">-</option>'.$loop_options.'</select></td>
					<td>glow: <input type="text" style="width:100px"></td>
					<td>blink: <select style="width:100px"><option value="">-</option>'.$num_options.'</select></td>
					<td>off: <select style="width:100px"><option value="">-</option>'.$num_options.'</select></td>
				</tr>';
			}
			echo '
			</table>
			<p><input type="submit" value="'.__( 'Generate shortcode' , 'neontext' ).'" id="neontext_generate"></p>
			<div id="sh_results" style="display:none;">
				<h3>Get your shortcode</h3>
				<p><textarea style="width:100%"></textarea>
			</div>
		</div>
	</div>
	<script>
	jQuery( document ).ready(function($) {
		$("#neontext_generate").click(function(e){
			e.preventDefault();
			
			var generate_lines = [];
			var sh_results = "[neontext_box]";
			
			for (i=0;i<5;i++) {
				j=i+1;
				
				var line_text=$("#line"+j).find("td").eq(1).find("input").val();
				var line_color=$("#line"+j).find("td").eq(2).find("input").val();
				var line_reblinkprobability=$("#line"+j).find("td").eq(3).find("select option:selected").val();
				var line_blinkmin=$("#line"+j).find("td").eq(4).find("select option:selected").val();
				var line_blinkmax=$("#line"+j).find("td").eq(5).find("select option:selected").val();
				var line_loopmin=$("#line"+j).find("td").eq(6).find("select option:selected").val();
				var line_loopmax=$("#line"+j).find("td").eq(7).find("select option:selected").val();
				var line_glow=$("#line"+j).find("td").eq(8).find("input").val();
				var line_blink=$("#line"+j).find("td").eq(9).find("select option:selected").val();
				var line_off=$("#line"+j).find("td").eq(10).find("select option:selected").val();
				
				generate_lines[i] = [line_text,line_color,line_reblinkprobability,line_blinkmin,line_blinkmax,line_loopmin,line_loopmax,line_glow,line_blink,line_off];
				
				if (line_text.length>0) {
					sh_results = sh_results + "[neontext";
					
					if (line_color.length>0) { sh_results = sh_results + " color=\""+line_color+"\""; }
					if (line_reblinkprobability.length>0) { sh_results = sh_results + " reblinkProbability=\""+line_reblinkprobability+"\""; }
					if (line_blinkmin.length>0) { sh_results = sh_results + " blinkmin=\""+line_blinkmin+"\""; }
					if (line_blinkmax.length>0) { sh_results = sh_results + " blinkmax=\""+line_blinkmax+"\""; }
					if (line_loopmin.length>0) { sh_results = sh_results + " loopmin=\""+line_loopmin+"\""; }
					if (line_loopmax.length>0) { sh_results = sh_results + " loopmax=\""+line_loopmax+"\""; }
					if (line_glow.length>0) { sh_results = sh_results + " glow=\""+line_glow+"\""; }
					if (line_blink.length>0) { sh_results = sh_results + " blink=\""+line_blink+"\""; }
					if (line_off.length>0) { sh_results = sh_results + " off=\""+line_off+"\""; }
					
					sh_results = sh_results + "]"+line_text+"[/neontext]";
				}
			}
			sh_results = sh_results + "[/neontext_box]";
			
			$("#sh_results").find("textarea").val(sh_results);			
			$("#sh_results").css("display","block");
		});
	});
	</script>
	';
}