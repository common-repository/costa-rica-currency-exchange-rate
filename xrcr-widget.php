<?php
/*
Plugin Name: Costa Rica Currency Exchange Rate
Plugin URI: http://www.artdecoded.net/blog/costa-rica-currency-exchange-rate/
Description: Shows the official Central Bank reference exchange rate from USD to CRC
Author: Pablo Alvarado
Version: 1.0.0
Author URI: http://www.artdecoded.net/
*/
class XRCostaRica_Widget extends WP_Widget
{
	function XRCostaRica_Widget() {
	$widget_ops = array('classname' => 'XRCostaRica_Widget', 'description' => 'Shows the official Central Bank reference exchange rate from USD to CRC' );
	$this->WP_Widget( 'XRCostaRica_Widget', 'Costa Rica Currency Exchange Rate', $widget_ops );
	}
 
	function form( $instance )
	{
	$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
	$title = ( $instance['title'] ) ? $instance['title'] : 'Costa Rica Currency Exchange Rate' ;
	?>
	<p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
	<?php
	}
 																																																																																																																																																																																																																																					
	function update( $new_instance, $old_instance )
	{
	$instance = $old_instance;
	$instance['title'] = $new_instance['title'];
	return $instance;
	}
 
 	function getIndicador( $indicadordate, $indicadorcode )
	{
	$LongURL = 'http://indicadoreseconomicos.bccr.fi.cr/indicadoreseconomicos/WebServices/wsIndicadoresEconomicos.asmx/ObtenerIndicadoresEconomicosXML?tcIndicador=' . $indicadorcode . '&tcFechaInicio=' . $indicadordate . '&tcFechaFinal=' . $indicadordate . '&tcNombre=Tester&tnSubNiveles=N';
	$XRurl = file_get_contents($LongURL);
	$XRurl = str_replace( '&lt;', '<', $XRurl );
	$XRurl = str_replace( '&gt;', '>', $XRurl );
	$indicadorXML = new SimpleXMLElement( $XRurl );
	$indicadorvalue = $indicadorXML->Datos_de_INGC011_CAT_INDICADORECONOMIC[0]->INGC011_CAT_INDICADORECONOMIC[0]->NUM_VALOR[0];
	$indicadorvalue = number_format($indicadorvalue, 2);
	return $indicadorvalue;
	}
 
	function widget( $args, $instance )
	{
	extract( $args, EXTR_SKIP );
 	echo $before_widget;
	$title = empty( $instance['title'] ) ? ' ' : apply_filters( 'widget_title', $instance['title'] );
	if ( ! empty($title) ) {
		echo $before_title . $title . $after_title;
		$today = date( 'd/m/Y' );
		$codigocompra = '317';
		$codigoventa  = '318';
		echo '<ul><li>Buy: &#8353; ' . $this->getIndicador( $today, $codigocompra ) . '</li><li>Sells: &#8353; ' . $this->getIndicador( $today, $codigoventa ) . '</li></ul>' ;
		echo $after_widget;
	}
  }
 
}
add_action( 'widgets_init', create_function('', 'return register_widget("XRCostaRica_Widget");') );?>