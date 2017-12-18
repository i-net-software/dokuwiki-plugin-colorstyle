<?php
/**
 * Site Export Plugin
 * 
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     i-net software <tools@inetsoftware.de>
 * @author     Gerry Weissbach <gweissbach@inetsoftware.de>
 */

// must be run within Dokuwiki
if(!defined('DOKU_INC')) define('DOKU_INC',realpath(dirname(__FILE__).'/../').'/');
if(!defined('DOKU_PLUGIN')) define('DOKU_PLUGIN',DOKU_INC.'lib/plugins/');
require_once(DOKU_PLUGIN.'action.php');

class action_plugin_color extends DokuWiki_Action_Plugin {
	
    /**
	* Register Plugin in DW
	**/
    public function register(Doku_Event_Handler $controller) {
        $controller->register_hook('TOOLBAR_DEFINE', 'AFTER', $this, 'color_toolbar_define');
        $controller->register_hook('TPL_METAHEADER_OUTPUT', 'BEFORE', $this, 'handle_tpl_metaheader_output');
	}
	
	public function color_toolbar_define(&$event) {
        $event->data[] = array (
            'type' => 'format',
            'title' => $this->getLang('warn'),
            'sample' => $this->getLang('warn'),
            'icon' => '../../plugins/color/images/warn.png',
            'open' => '<color #ff3300>',
            'close' => ':</color>',
            'block' => false,
        );
        $event->data[] = array (
            'type' => 'format',
            'title' => $this->getLang('note'),
            'sample' => $this->getLang('note'),
            'icon' => '../../plugins/color/images/hint.png',
            'open' => '<color #ff3300>',
            'close' => ':</color>',
            'block' => false,
        );
	}

    /**
     * [Custom event handler which performs action]
     *
     * @param Doku_Event $event  event object by reference
     * @param mixed      $param  [the parameters passed as fifth argument to register_hook() when this
     *                           handler was registered]
     * @return void
     */
    public function handle_tpl_metaheader_output(Doku_Event &$event, $param) {
        global $ID;

        $style = '';
        $metadata = p_get_metadata( $ID, 'plugin_colorstyle')?:array();
        foreach( $metadata as $colorType => $colors ) {
            foreach( array_unique($colors) as $color ) {
                $style .= '.color_' . dechex(crc32($color)) . '{' . $colorType . ':' . $color . ";}\n";
            }
        }

        $event->data['style'][] = array(
            '_data' => $style
        );
    }
}
