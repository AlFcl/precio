<?php
/**
 * Plugin Name: Alfcl Precio
 * Description: Calculadora de precios creada por alf.cl, con IVA, margen, utilidad y venta sugerida.
 * Version: 0.1.0
 * Author: alf.cl
 * Author URI: https://alf.cl
 * Text Domain: alfcl-precio
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

define( 'ALFCL_PRECIO_VERSION', '0.1.0' );
define( 'ALFCL_PRECIO_FILE', __FILE__ );
define( 'ALFCL_PRECIO_DIR', plugin_dir_path( __FILE__ ) );
define( 'ALFCL_PRECIO_URL', plugin_dir_url( __FILE__ ) );

add_shortcode( 'alf_precio', 'alfcl_precio_shortcode' );
add_shortcode( 'alfcl_precio', 'alfcl_precio_shortcode' );

function alfcl_precio_shortcode( $atts ) {
    $atts = shortcode_atts(
        [
            'iva'      => '19',
            'ganancia' => '25',
            'bebidas'  => '20',
        ],
        $atts,
        'alf_precio'
    );

    $iva_rate    = alfcl_precio_percent_attr( $atts['iva'] );
    $profit_rate = alfcl_precio_percent_attr( $atts['ganancia'] );
    $drink_rate  = alfcl_precio_percent_attr( $atts['bebidas'] );
    $uid         = 'alf-price-tool-' . wp_generate_uuid4();

    wp_enqueue_style(
        'alfcl-precio',
        ALFCL_PRECIO_URL . 'assets/css/price-calculator.css',
        [],
        ALFCL_PRECIO_VERSION
    );

    wp_enqueue_script(
        'alfcl-precio',
        ALFCL_PRECIO_URL . 'assets/js/price-calculator.js',
        [],
        ALFCL_PRECIO_VERSION,
        true
    );

    ob_start();
    ?>
    <section id="<?php echo esc_attr( $uid ); ?>" class="alf-price-tool" data-iva="<?php echo esc_attr( $iva_rate ); ?>" data-profit="<?php echo esc_attr( $profit_rate ); ?>" data-drink="<?php echo esc_attr( $drink_rate ); ?>">
        <header class="alf-price-tool-hero">
            <span class="alf-price-tool-kicker"><?php echo esc_html__( 'Herramienta de alf.cl', 'alfcl-precio' ); ?></span>
            <h1><?php echo esc_html__( 'Calculadora de precios', 'alfcl-precio' ); ?></h1>
            <p><?php echo esc_html__( 'Configura tus porcentajes y calcula costo unitario, IVA, utilidad y precio de venta sugerido.', 'alfcl-precio' ); ?></p>
        </header>

        <div class="alf-price-settings" aria-label="<?php echo esc_attr__( 'Configuracion de porcentajes', 'alfcl-precio' ); ?>">
            <label><span><?php echo esc_html__( 'IVA (%)', 'alfcl-precio' ); ?></span><input type="number" min="0" step="0.1" inputmode="decimal" data-setting="iva" value="<?php echo esc_attr( $iva_rate * 100 ); ?>"></label>
            <label><span><?php echo esc_html__( 'Ganancia general (%)', 'alfcl-precio' ); ?></span><input type="number" min="0" step="0.1" inputmode="decimal" data-setting="profit" value="<?php echo esc_attr( $profit_rate * 100 ); ?>"></label>
            <label><span><?php echo esc_html__( 'Ganancia bebidas (%)', 'alfcl-precio' ); ?></span><input type="number" min="0" step="0.1" inputmode="decimal" data-setting="drink" value="<?php echo esc_attr( $drink_rate * 100 ); ?>"></label>
        </div>

        <div class="alf-price-tool-grid" role="tablist" aria-label="<?php echo esc_attr__( 'Modo de calculo', 'alfcl-precio' ); ?>">
            <button type="button" class="alf-price-tab is-active" data-mode="neto" role="tab" aria-selected="true"><?php echo esc_html__( 'Desde neto', 'alfcl-precio' ); ?></button>
            <button type="button" class="alf-price-tab" data-mode="bruto" role="tab" aria-selected="false"><?php echo esc_html__( 'Desde bruto', 'alfcl-precio' ); ?></button>
            <button type="button" class="alf-price-tab" data-mode="bebidas" role="tab" aria-selected="false"><?php echo esc_html__( 'Bebidas', 'alfcl-precio' ); ?></button>
        </div>

        <div class="alf-price-panel is-active" data-panel="neto" role="tabpanel">
            <div class="alf-price-form">
                <label><span><?php echo esc_html__( 'Cantidad de productos', 'alfcl-precio' ); ?></span><input type="number" min="1" step="1" inputmode="numeric" data-field="neto-cantidad" placeholder="Ej: 12"></label>
                <label><span><?php echo esc_html__( 'Total neto de la compra', 'alfcl-precio' ); ?></span><input type="number" min="0" step="1" inputmode="numeric" data-field="neto-total" placeholder="Ej: 24000"></label>
                <button type="button" class="alf-price-action" data-action="neto"><?php echo esc_html__( 'Calcular', 'alfcl-precio' ); ?></button>
            </div>
            <div class="alf-price-results" aria-live="polite">
                <div><span><?php echo esc_html__( 'Compra unitaria', 'alfcl-precio' ); ?></span><strong data-result="neto-compra">$0</strong></div>
                <div><span><?php echo esc_html__( 'IVA por unidad', 'alfcl-precio' ); ?></span><strong data-result="neto-iva">$0</strong></div>
                <div><span><?php echo esc_html__( 'Venta sugerida', 'alfcl-precio' ); ?></span><strong data-result="neto-venta">$0</strong></div>
                <div><span><?php echo esc_html__( 'Utilidad estimada', 'alfcl-precio' ); ?></span><strong data-result="neto-utilidad">$0</strong></div>
            </div>
        </div>

        <div class="alf-price-panel" data-panel="bruto" role="tabpanel" hidden>
            <div class="alf-price-form">
                <label><span><?php echo esc_html__( 'Cantidad de productos', 'alfcl-precio' ); ?></span><input type="number" min="1" step="1" inputmode="numeric" data-field="bruto-cantidad" placeholder="Ej: 12"></label>
                <label><span><?php echo esc_html__( 'Total bruto de la compra', 'alfcl-precio' ); ?></span><input type="number" min="0" step="1" inputmode="numeric" data-field="bruto-total" placeholder="Ej: 28560"></label>
                <button type="button" class="alf-price-action" data-action="bruto"><?php echo esc_html__( 'Calcular', 'alfcl-precio' ); ?></button>
            </div>
            <div class="alf-price-results" aria-live="polite">
                <div><span><?php echo esc_html__( 'Neto unitario', 'alfcl-precio' ); ?></span><strong data-result="bruto-compra">$0</strong></div>
                <div><span><?php echo esc_html__( 'IVA incluido', 'alfcl-precio' ); ?></span><strong data-result="bruto-iva">$0</strong></div>
                <div><span><?php echo esc_html__( 'Venta sugerida', 'alfcl-precio' ); ?></span><strong data-result="bruto-venta">$0</strong></div>
                <div><span><?php echo esc_html__( 'Utilidad estimada', 'alfcl-precio' ); ?></span><strong data-result="bruto-utilidad">$0</strong></div>
            </div>
        </div>

        <div class="alf-price-panel" data-panel="bebidas" role="tabpanel" hidden>
            <div class="alf-price-form">
                <label><span><?php echo esc_html__( 'Botellas por pack', 'alfcl-precio' ); ?></span><input type="number" min="1" step="1" inputmode="numeric" data-field="bebidas-pack" placeholder="Ej: 6"></label>
                <label><span><?php echo esc_html__( 'Cantidad de packs', 'alfcl-precio' ); ?></span><input type="number" min="1" step="1" inputmode="numeric" data-field="bebidas-cantidad" placeholder="Ej: 4"></label>
                <label><span><?php echo esc_html__( 'Valor por botella', 'alfcl-precio' ); ?></span><input type="number" min="0" step="1" inputmode="numeric" data-field="bebidas-botella" placeholder="Ej: 900"></label>
                <button type="button" class="alf-price-action" data-action="bebidas"><?php echo esc_html__( 'Calcular', 'alfcl-precio' ); ?></button>
            </div>
            <div class="alf-price-results is-wide" aria-live="polite">
                <div><span><?php echo esc_html__( 'Botellas totales', 'alfcl-precio' ); ?></span><strong data-result="bebidas-total">0</strong></div>
                <div><span><?php echo esc_html__( 'Total neto compra', 'alfcl-precio' ); ?></span><strong data-result="bebidas-compra-total">$0</strong></div>
                <div><span><?php echo esc_html__( 'Neto por botella', 'alfcl-precio' ); ?></span><strong data-result="bebidas-compra">$0</strong></div>
                <div><span><?php echo esc_html__( 'IVA por botella', 'alfcl-precio' ); ?></span><strong data-result="bebidas-iva">$0</strong></div>
                <div><span><?php echo esc_html__( 'Venta sugerida', 'alfcl-precio' ); ?></span><strong data-result="bebidas-venta">$0</strong></div>
                <div><span><?php echo esc_html__( 'Utilidad por botella', 'alfcl-precio' ); ?></span><strong data-result="bebidas-utilidad">$0</strong></div>
            </div>
        </div>

        <p class="alf-price-note"><?php echo esc_html__( 'Los valores son sugerencias de referencia. Ajusta los porcentajes segun tu negocio, costos fijos y redondeo comercial.', 'alfcl-precio' ); ?></p>
    </section>
    <?php
    return ob_get_clean();
}

function alfcl_precio_percent_attr( $value ) {
    $value = is_scalar( $value ) ? (string) $value : '0';
    $value = str_replace( ',', '.', $value );
    return max( 0, (float) $value ) / 100;
}
