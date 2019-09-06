<?php
/**
 * @author      Julian Bogdani <jbogdani@gmail.com>
 * @copyright    BraDyUS. Communicating Cultural Heritage, http://bradypus.net 2007-2013
 * @license      MIT, See LICENSE file
 * @since      October 16, 2015
 */

class Assets
{

  /**
   * Returns array with assets data
   * @return array Array with assets data
   */
    private static function allAssets()
    {
        $path = link::to_article('home');

        $assets = array(

      'frontend' => [
        'js' => [
          'local' => $path . 'sites/default/js/frontend.js?' . md5_file('sites/default/css/styles.css')
        ],
        'css' => [
          'local' => $path . 'sites/default/css/styles.css?' . md5_file('sites/default/css/styles.css')
        ]
      ],

      'jquery' => [
        'version' => '3.3.1',
        'js' => [
          'local' => $path . 'frontLibs/jquery/dist/jquery.min.js',
          'cdn' => 'https://code.jquery.com/jquery---version--.min.js'
        ]
      ],

      'jquery.slim' => [
        'version' => '3.3.1',
        'js' => [
          'cdn' => 'https://code.jquery.com/jquery---version--.slim.min.js'
        ]
      ],

      'bootstrap4' => [
        'version' => '4.3.1',
        'js' => [
          'local' => $path . 'frontLibs/bootstrap/dist/js/bootstrap.min.js',
          'cdn' => 'https://stackpath.bootstrapcdn.com/bootstrap/--version--/js/bootstrap.min.js'
        ],
        'css' => [
          'local' => $path . 'frontLibs/bootstrap/dist/css/bootstrap.min.css',
          'cdn' => 'https://stackpath.bootstrapcdn.com/bootstrap/--version--/css/bootstrap.min.css'
        ]
      ],

      'bootstrap' => [
        'version' => '3.3.5',
        'js' => [
          'local' => $path . 'frontLibs/bootstrap3/dist/js/bootstrap.min.js',
          'cdn' => 'https://stackpath.bootstrapcdn.com/bootstrap/--version--/js/bootstrap.min.js'
        ],
        'css' => [
          'local' => $path . 'frontLibs/bootstrap3/dist/css/bootstrap.min.css',
          'cdn' => 'https://stackpath.bootstrapcdn.com/bootstrap/--version--/css/bootstrap.min.css'
        ]
      ],

      'fancybox' => [
        'version' => '3.3.5',
        'js' => [
          'local' => $path . 'frontLibs/@fancyapps/fancybox/dist/jquery.fancybox.min.js',
          'cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.js'
        ],
        'css' => [
          'local' => $path . 'frontLibs/@fancyapps/fancybox/dist/jquery.fancybox.min.css',
          'cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.3.5/jquery.fancybox.min.css'
        ]
      ],

      'modernizr' => [
        'version' => '3.6.0',
        'js' => [
          'cdn' => 'https://cdnjs.cloudflare.com/ajax/libs/modernizr/--version--/modernizr.min.js'
        ]
      ],

      'font-awesome' => [
        'version' => '4.7.0',
        'css' => [
          'cdn' => 'https://maxcdn.bootstrapcdn.com/font-awesome/--version--/css/font-awesome.min.css',
          'local' => $path . 'frontLibs/font-awesome/css/font-awesome.min.css'
        ]
      ]
    );

        return $assets;
    }

    /**
     * Returns array with names of available assets
     * @return array arry with available assets
     */
    public static function getNames()
    {
        return array_keys(self::allAssets($path));
    }

    /**
     * returns array with asset data
     * @param  string $name    Asset's name
     * @param  string|false $type    Asset's type, one of: js|css. If missing and asset has both types js will be used, otherwise available type will be returned
     * @param  string|false $source    One of: cdn|local. If missing and asset has both sources cdn will be used, otherwise available source will be returned
     * @param  string|false $version Asset's version. If missing defaultversion will be used
     * @return array|false        Asset's data array
     */
    public static function resolve($name, $type = false, $source = false, $version = false)
    {
        // Return false if type is defined but is not css or js
        if ($type && $type !== 'css' && $type !== 'js') {
            return false;
        }

        // Return false if dest is defined but is not cdn or local
        if ($source && $source !== 'local' && $source !== 'cdn') {
            return false;
        }


        $assets = self::allAssets();

        // Return false on no asset found
        if (!isset($assets[$name])) {
            return false;
        }

        $asset = $assets[$name];


        // Set type if not setted
        if (!$type && isset($asset['css']) && isset($asset['js'])) {
            $type = 'js';
        } elseif (!$type && isset($asset['css']) && !isset($asset['js'])) {
            $type = 'css';
        } elseif (!$type && !isset($asset['css']) && isset($asset['js'])) {
            $type = 'js';
        } elseif (!isset($asset[$type])) {
            return false;
        }

        // Set $source
        if (!$source && isset($asset[$type]['cdn'])) {
            // 1. No custom source supplied: use default value: CDN, if available
            $source = 'cdn';
        } elseif (!$source && isset($asset[$type]['local'])) {
            // 2. No custom source supplied, no available CDN value: use LOCAL if available
            $source = 'local';
        } elseif ($source && !isset($asset[$type][$source])) {
            // 3. Source supplied and is one of cdn|local.
            // If custom value is not available check for alternative value or return false
            if (isset($asset[$type]['cdn'])) {
                $source = 'cdn';
            } elseif (isset($asset[$type]['local'])) {
                $source = 'local';
            } else {
                return false;
            }
        }

        if ($version) {
            return [
              'type' =>$type,
              'source' => $ource,
              'path' => str_replace('--version--', $version, $asset[$type][$source])
            ];
        } elseif (isset($asset['version'])) {
            return [
              'type' =>$type,
              'source' => $ource,
              'path' => str_replace('--version--', $asset['version'], $asset[$type][$source])
            ];
        } else {
            return [
              'type' =>$type,
              'source' => $ource,
              'path' => $asset[$type][$source]
            ];
        }
    }
}
