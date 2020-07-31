<?php

namespace UkrSolution\WpBarcodesGenerator;

require_once ABSPATH.'wp-admin/includes/upgrade.php'; // dbDelta function include

class Database
{
    /**
     * Method of creating a table in the database when the plugin is activated.
     */
    public static function setupTables($network_wide)
    {
        global $wpdb;

        // If is network, create tables for each blog.
        if (is_multisite() && $network_wide) {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            // Create tables for each blog
            foreach ($blog_ids as $blog_id) {
                switch_to_blog($blog_id);
                self::createTables();
                restore_current_blog();
            }
        } else {
            self::createTables();
        }

        self::createTables();
    }

    /**
     * Create tables.
     */
    public static function createTables()
    {
        self::setupFormatsTables();
        self::setupTemplatesTable();
    }

    /**
     * Setup formats tables.
     */
    protected static function setupFormatsTables()
    {
        global $wpdb;

        $tblPaperFormats = $wpdb->prefix.'a4barcode_paper_formats';
        // Check to see if the table exists already, if not, then create it
        $sql = "CREATE TABLE `{$tblPaperFormats}` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `width` decimal(12,4) DEFAULT '0.0000',
            `height` decimal(12,4) DEFAULT '0.0000',
            `default` tinyint(1) NOT NULL DEFAULT '0',
            `uol` varchar(16) DEFAULT 'mm' NULL,
            `landscape` tinyint(1) DEFAULT 0 NOT NULL,
            `is_editable` tinyint(1) DEFAULT 1 NOT NULL,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($sql);

        $tblSheetFormat = $wpdb->prefix.'a4barcode_custom_formats';
        // Check to see if the table exists already, if not, then create it
        $sql = "CREATE TABLE `{$tblSheetFormat}` (
            `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
            `userId` int(11) DEFAULT NULL,
            `name` varchar(255) DEFAULT NULL,
            `width` decimal(12,4) DEFAULT NULL,
            `height` decimal(12,4) DEFAULT NULL,
            `arround` decimal(12,4) DEFAULT NULL,
            `across` decimal(12,4) DEFAULT NULL,
            `marginLeft` decimal(12,4) DEFAULT NULL,
            `marginRight` decimal(12,4) DEFAULT NULL,
            `marginTop` decimal(12,4) DEFAULT NULL,
            `marginBottom` decimal(12,4) DEFAULT NULL,
            `arroundCount` int(11) DEFAULT NULL,
            `acrossCount` int(11) DEFAULT NULL,
            `datetime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            `paperId` int(11) unsigned DEFAULT '1' COMMENT 'linked to install',
            `default` tinyint(1) NOT NULL DEFAULT '0',
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($sql);

        // Data for table labels formats
        $dataLabelsFormats = require __DIR__.'/../config/labels.php';

        // Data for table paper formats
        $dataPaperFormats = require __DIR__.'/../config/papers.php';

        // Each preinstalled paper format
        for ($i = 0; $i < count($dataPaperFormats); ++$i) {
//            $paperFormat = $wpdb->get_results(
//                $wpdb->prepare("
//                        SELECT *
//                        FROM `{$tblPaperFormats}`
//                        WHERE (`id` = %s AND `default` = 1) || `id` = 4 # id=4 thermal printer is special(not default - do not update, only insert if not exists)
//                    ",
//                    array($dataPaperFormats[$i]['id'])
//                )
//            );
            $paperFormat = $wpdb->get_row(
                $wpdb->prepare("
                        SELECT * 
                        FROM `{$tblPaperFormats}` 
                        WHERE (`id` = %s)
                    ",
                    array($dataPaperFormats[$i]['id'])
                )
            );

            // Client don't have this format
            if (!$paperFormat) {
                // Insert it in
                $wpdb->insert($tblPaperFormats, $dataPaperFormats[$i]);

                $insertId = $wpdb->insert_id;

                // if there is data to insert into the table
                if (count($dataLabelsFormats) > 0) {
                    // loop through the data and insert into the table
                    foreach ($dataLabelsFormats as $label) {
                        $label['userId'] = get_current_user_id();
                        // Find and put sheet formats for new paper format
                        if ($i + 1 == $label['paperId']) {
                            $label['paperId'] = $insertId;
                            $wpdb->insert($tblSheetFormat, $label);
                        }
                    }
                }
            } else {
                // Update only default formats. id=4 - thermal printer is special. Sinse 2.11.6 it is should be default to prevent users delete it.
//                if (1 === $dataPaperFormats[$i]['default'] && 4 !== $dataPaperFormats[$i]['id']) {
                if ('1' === $paperFormat->default && '4' !== $paperFormat->id) {
                    // Update default paper format
                    $wpdb->update($tblPaperFormats,
                        array(
                            'name' => $dataPaperFormats[$i]['name'],
                            'width' => $dataPaperFormats[$i]['width'],
                            'height' => $dataPaperFormats[$i]['height'],
                            'default' => $dataPaperFormats[$i]['default'],
                            'uol' => $dataPaperFormats[$i]['uol'],
                            'is_editable' => $dataPaperFormats[$i]['is_editable'],
                        ),
                        array('id' => $dataPaperFormats[$i]['id'])
                    );
                } elseif (4 === $dataPaperFormats[$i]['id']) {
                    // id=4 - thermal printer is special. Sinse 2.11.6 it is should be default to prevent users delete it.
                    // Also width and height shouldn't be changed, so update only 'default' column
                    $wpdb->update($tblPaperFormats,
                        array(
                            'default' => $dataPaperFormats[$i]['default'],
                            'is_editable' => $dataPaperFormats[$i]['is_editable'],
                        ),
                        array('id' => $dataPaperFormats[$i]['id'])
                    );
                }
            }
        }
    }

    /**
     * Setup custom templates table.
     */
    protected static function setupTemplatesTable()
    {
        global $wpdb;

        $tbl = $wpdb->prefix.'a4barcode_custom_templates';

        // Check to see if the table exists already, if not, then create it
        $sql = "CREATE TABLE `{$tbl}` (
            `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
            `name` varchar(255) DEFAULT NULL,
            `slug` varchar(255) DEFAULT NULL,
            `template` TEXT,
            `is_active` tinyint(1) NOT NULL DEFAULT '0',
            `is_default` tinyint(1) NOT NULL DEFAULT '0',
            `is_base` tinyint(1) NOT NULL DEFAULT '0',
            `height` decimal(12,4) DEFAULT 37 NULL,
            `width` decimal(12,4) DEFAULT 70 NULL,
            `uol` varchar(16) DEFAULT 'mm' NULL,
            `base_padding` int NOT NULL DEFAULT '8',   
            `base_padding_uol` decimal(12,4) NULL,
            `code_match` TINYINT(4) DEFAULT 0  NOT NULL,
            `single_product_code` TEXT,            
            `variable_product_code` TEXT,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        dbDelta($sql);

        $data = require __DIR__.'/../config/templates.php';
        // Insert data into table
        foreach ($data as $datum) {
            // Replace image url path
            $datum['template'] = preg_replace('/\[logo_img_url]/', plugin_dir_url(dirname(__FILE__)).'assets/img/amazon-200x113.jpg', $datum['template']);

            // Check for datum already exists.
            $datumExists = $wpdb->get_results(
                $wpdb->prepare("SELECT * FROM `{$tbl}` WHERE `slug` = %s AND `is_default` = 1", array($datum['slug']))
            );

            // If row already exists - update it, otherwise - insert.
            if ($datumExists) {
                // Update only tmpl content
                $wpdb->update(
                    $tbl,
                    array(
                        'name' => $datum['name'],
                        'template' => $datum['template'],
                        'width' => $datum['width'],
                        'height' => $datum['height'],
                        'uol' => $datum['uol'],
                    ),
                    array('slug' => $datum['slug'], 'is_default' => 1)
                );
            } else {
                // Insert
                $wpdb->insert($tbl, $datum);
            }
        }
    }
}
