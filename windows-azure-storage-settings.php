<?php
/**
 * Shows various settings for Microsoft Azure Storage Plugin
 *
 * Version: 3.0.1
 *
 * Author: Microsoft Open Technologies, Inc.
 *
 * Author URI: http://www.microsoft.com/
 *
 * License: New BSD License (BSD)
 *
 * Copyright (c) Microsoft Open Technologies, Inc.
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * Redistributions of source code must retain the above copyright notice, this list
 * of conditions and the following disclaimer.
 * Redistributions in binary form must reproduce the above copyright notice, this
 * list of conditions  and the following disclaimer in the documentation and/or
 * other materials provided with the distribution.
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND
 * ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A  PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR
 * ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES
 * (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS
 * OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)  HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
 * NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
 * IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP Version 5
 *
 * @category  WordPress_Plugin
 * @package   Microsoft_Azure_Storage_For_WordPress
 * @author    Microsoft Open Technologies, Inc. <msopentech@microsoft.com>
 * @copyright Microsoft Open Technologies, Inc.
 * @license   New BSD license, (http://www.opensource.org/licenses/bsd-license.php)
 * @link      http://www.microsoft.com
 */

/**
 * Preamble text on Microsoft Azure Storage plugin settings page.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_plugin_settings_preamble() {
	?><div class="wrap">
		<h2>
			<img src="<?php echo esc_url( MSFT_AZURE_PLUGIN_URL . 'images/azure-icon.png' ); ?>" alt="<?php esc_attr_e( 'Microsoft Azure', 'windows-azure-storage' ); ?>" style="width:32px">
			<?php esc_html_e( 'Microsoft Azure Storage for WordPress', 'windows-azure-storage' ); ?>
		</h2>

		<p>
			<?php esc_html_e(
				'This WordPress plugin allows you to use Microsoft Azure Storage Service to host your media for your WordPress powered blog. Microsoft Azure provides storage in the cloud with authenticated access and triple replication to help keep your data safe. Applications work with data using REST conventions and standard HTTP operations to identify and expose data using URIs. This plugin allows you to easily upload, retrieve, and link to files stored on Microsoft Azure Storage service from within WordPress.',
				'windows-azure-storage'
			); ?>
		</p>

		<p style="margin-bottom:4em">
			<?php echo __( 'For more details on Microsoft Azure Storage Services, please visit the <a href="https://azure.microsoft.com/en-us/">Microsoft Azure Platform web-site</a>.', 'windows-azure-storage' ); ?><br>
			<b><?php esc_html_e( 'Plugin Web Site:', 'windows-azure-storage' ); ?></b>
			<a href="https://wordpress.org/plugins/windows-azure-storage/">https://wordpress.org/plugins/windows-azure-storage/</a>
		</p>
	</div><?php
}

/**
 * WordPress hook for displaying plugin options page.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_plugin_options_page() {
	windows_azure_storage_plugin_settings_preamble();
	?>
	<div class="wrap">
		<div id="icon-options-general" class="icon32"><br/></div>
		<form method="post" name="azure-settings-form" id="azure-settings-form" action="options.php">
			<?php
			settings_fields( 'windows-azure-storage-settings-group' );
			do_settings_sections( 'windows-azure-storage-plugin-options' );
			submit_button( __( 'Save Changes', 'windows-azure-storage' ), 'submit primary', 'azure-submit-button', true );
			?>
		</form>
	</div>
	<?php
}

/**
 * Register custom settings for Microsoft Azure Storage Plugin.
 *
 * @return void
 */
function windows_azure_storage_plugin_register_settings() {
	register_setting( 'windows-azure-storage-settings-group', 'azure_storage_keep_local_file', 'wp_validate_boolean' );
	register_setting( 'windows-azure-storage-settings-group', 'azure_browse_cache_results', 'intval' );

	if ( ! defined( 'MICROSOFT_AZURE_ACCOUNT_NAME' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'azure_storage_account_name', 'sanitize_text_field' );
	}

	if ( ! defined( 'MICROSOFT_AZURE_ACCOUNT_KEY' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'azure_storage_account_primary_access_key', 'sanitize_text_field' );
	}

	if ( ! defined( 'MICROSOFT_AZURE_CONTAINER' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'default_azure_storage_account_container_name', 'sanitize_text_field' );
	}

	if ( ! defined( 'MICROSOFT_AZURE_CNAME' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'cname', 'esc_url_raw' );
	}

    if ( ! defined( 'MICROSOFT_AZURE_EXCLUDE_CONTAINER_FROM_URL' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'azure_storage_exclude_container_from_url', 'wp_validate_boolean' );
	}

	if ( ! defined( 'MICROSOFT_AZURE_USE_FOR_DEFAULT_UPLOAD' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'azure_storage_use_for_default_upload', 'wp_validate_boolean' );
	}

	if ( ! defined( 'MICROSOFT_AZURE_CACHE_CONTROL' ) ) {
		register_setting( 'windows-azure-storage-settings-group', 'azure_cache_control', 'sanitize_text_field' );
	}

	/**
	 * @since 4.0.0
	 */
	add_settings_section(
		'windows-azure-storage-settings',
		__( 'Microsoft Azure Storage Settings', 'windows-azure-storage' ),
		'windows_azure_storage_plugin_settings_section',
		'windows-azure-storage-plugin-options'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_account_name',
		__( 'Store Account Name', 'windows-azure-storage' ),
		'windows_azure_storage_setting_account_name',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_account_key',
		__( 'Store Account Key', 'windows-azure-storage' ),
		'windows_azure_storage_setting_account_key',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_default_container',
		__( 'Default Storage Container', 'windows-azure-storage' ),
		'windows_azure_storage_setting_storage_container',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_cname',
		__( 'CNAME', 'windows-azure-storage' ),
		'windows_azure_storage_setting_cname',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
    /**
	 * @since 4.x.x
	 */
	add_settings_field(
		'azure_storage_exclude_container_from_url',
		__( 'Exclude container in CNAME URL', 'windows-azure-storage' ),
		'windows_azure_storage_setting_exclude_container_from_url',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_handle_uploads',
		__( 'Use for default upload', 'windows-azure-storage' ),
		'windows_azure_storage_setting_handle_uploads',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_storage_keep_local_file',
		__( 'Keep local files', 'windows-azure-storage' ),
		'windows_azure_storage_setting_keep_local_file',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.0.0
	 */
	add_settings_field(
		'azure_browse_cache_results',
		__( 'Timeout for azure file list cache in seconds', 'windows-azure-storage' ),
		'windows_azure_browse_cache_results',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
	/**
	 * @since 4.1.0
	 */
	add_settings_field(
		'azure_cache_control',
		__( 'Cache control in seconds', 'windows-azure-storage' ),
		'windows_azure_cache_control',
		'windows-azure-storage-plugin-options',
		'windows-azure-storage-settings'
	);
}

/**
 * Settings section callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_plugin_settings_section() {
	?>
	<p><?php echo __( 'If you do not have Microsoft Azure Storage Account, please <a href="https://azure.microsoft.com/en-us/free/">register </a>for Microsoft Azure Services.', 'windows-azure-storage' ); ?></p>
	<?php
}


/**
 * Account name setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_account_name() {
	$storage_account_name = Windows_Azure_Helper::get_account_name();

	if ( defined( 'MICROSOFT_AZURE_ACCOUNT_NAME' ) ) {
		echo '<input type="text" class="regular-text" value="', esc_attr( $storage_account_name ), '" readonly disabled>';
	} else {
		echo '<input type="text" name="azure_storage_account_name" class="regular-text" value="', esc_attr( $storage_account_name ), '">';
	}

	echo '<p>';
		_e( 'Microsoft Azure Storage Account Name. You can define <code>MICROSOFT_AZURE_ACCOUNT_NAME</code> constant to override it.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * Account key setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_account_key() {
	$storage_account_key = Windows_Azure_Helper::get_account_key();

	if ( defined( 'MICROSOFT_AZURE_ACCOUNT_KEY' ) ) {
		echo '<input type="text" class="large-text" value="', esc_attr( $storage_account_key ), '" readonly disabled>';
	} else {
		echo '<input type="text" name="azure_storage_account_primary_access_key" class="large-text" value="', esc_attr( $storage_account_key ), '">';
	}

	echo '<p>';
		_e( 'Microsoft Azure Storage Account Primary Access Key. You can define <code>MICROSOFT_AZURE_ACCOUNT_KEY</code> constant to override it.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * Default storage container setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_storage_container() {
	$default_container = Windows_Azure_Helper::get_default_container();

	if ( defined( 'MICROSOFT_AZURE_CONTAINER' ) ) {
		echo '<input type="text" class="regular-text" value="', $default_container, '" readonly disabled>';
	} else {
		$containers_list = Windows_Azure_Helper::list_containers();
		$new_container_name = isset( $_POST['newcontainer'] ) ? sanitize_text_field( wp_unslash( $_POST['newcontainer'] ) ) : '';
		$container_creation_failed = apply_filters( 'windows_azure_storage_container_creation_failed', false );

		?><select name="default_azure_storage_account_container_name" class="azure-container-selector regular-text"><?php
			if ( ! is_wp_error( $containers_list ) ) {
				foreach ( $containers_list as $container ) {
					if ( empty( $default_container ) ) {
						$default_container = $container['Name'];
						Windows_Azure_Helper::set_default_container( $default_container );
					}

					?><option value="<?php echo esc_attr( $container['Name'] ); ?>"
						<?php if ( ! $container_creation_failed ) {
							selected( $container['Name'], $default_container );
						} ?>>
						<?php echo esc_html( $container['Name'] ); ?>
					</option><?php
				}
				if ( current_user_can( 'manage_options' ) ) {
					?><option value="__newContainer__" <?php selected( $container_creation_failed ); ?>>
						&mdash;&thinsp;<?php esc_html_e( 'Create New Container', 'windows-azure-storage' ); ?>&thinsp;&mdash;
					</option><?php
				}
			}
		?></select><?php

		if ( current_user_can( 'manage_options' ) ) :
			wp_nonce_field( 'create_container', 'create_new_container_settings' );
			?><div id="div-create-container" name="div-create-container" <?php if ( ! $container_creation_failed ) : ?>style="display:none;"<?php endif; ?>>
				<p>
					<label for="newcontainer" title="<?php __( 'Name of the new container to create', 'windows-azure-storage' ); ?>"><?php echo __( 'New container name: ', 'windows-azure-storage' ); ?></label>
					<input type="text" name="newcontainer" class="regular-text" title="<?php __( 'Name of the new container to create', 'windows-azure-storage' ); ?>" value="<?php echo esc_attr( $new_container_name ); ?>"/>
				</p>
				<p>
					<input type="button" class="button-primary azure-create-container-button" value="<?php esc_attr_e( 'Create', 'windows-azure-storage' ); ?>" data-container-url="<?php echo esc_attr( sprintf( '%s', esc_url( $_SERVER['REQUEST_URI'] ) ) ); ?>"/>
				</p>
			</div><?php
		endif;
	}

	echo '<p>';
		_e( 'Default container to be used for storing media files. You can define <code>MICROSOFT_AZURE_CONTAINER</code> constant to override it.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * CNAME setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_cname() {
	$cname = Windows_Azure_Helper::get_cname();

	if ( defined( 'MICROSOFT_AZURE_CNAME' ) ) {
		echo '<input type="url" class="regular-text" value="', esc_attr( $cname ), '" readonly disabled>';
	} else {
		echo '<input type="url" name="cname" class="regular-text" value="', esc_attr( $cname ), '">';
	}

	echo '<p>';
		_e( 'Use this option if you would like to display image URLs belonging to your domain like <code>http://mydomain.com/</code> instead of <code>http://your-account-name.blob.core.windows.net/</code>. This CNAME must start with <code>http(s)://</code> and the administrator will have to update <abbr title="Domain Name System">DNS</abbr> entries accordingly. You can use <code>MICROSOFT_AZURE_CNAME</code> constant to override it.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * CNAME setting callback function.
 *
 * @since 4.x.x
 *
 * @return void
 */
function windows_azure_storage_setting_exclude_container_from_url() {
	$exclude_container = Windows_Azure_Helper::exclude_container_from_url();

	if ( defined( 'MICROSOFT_AZURE_EXCLUDE_CONTAINER_FROM_URL' ) ) {
		echo '<input type="checkbox" id="azure_storage_exclude_container_from_url"', checked( $exclude_container, true, false ), ' readonly disabled>';
	} else {
		echo '<input type="checkbox" name="azure_storage_exclude_container_from_url" value="1" id="azure_storage_exclude_container_from_url"', checked( $exclude_container, true, false ), '>';
	}

	echo '<label for="azure_storage_exclude_container_from_url">';
		esc_html_e( 'Exclude the storage container name in the url when using a CNAME', 'windows-azure-storage' );
	echo '</label>';

	echo '<p>';
		_e( 'Check this if your CNAME points directly to a specific container. This setting only applies when a CNAME is set. This setting can be overriden using the <code>MICROSOFT_AZURE_EXCLUDE_CONTAINER_FROM_URL</code> PHP constant.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * Account key setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_handle_uploads() {
	$use_for_upload = Windows_Azure_Helper::get_use_for_default_upload();

	if ( defined( 'MICROSOFT_AZURE_USE_FOR_DEFAULT_UPLOAD' ) ) {
		echo '<input type="checkbox" id="azure_storage_use_for_default_upload"', checked( $use_for_upload, true, false ), ' readonly disabled>';
	} else {
		echo '<input type="checkbox" name="azure_storage_use_for_default_upload" value="1" id="azure_storage_use_for_default_upload"', checked( $use_for_upload, true, false ), '>';
	}

	echo '<label for="azure_storage_use_for_default_upload">';
		esc_html_e( 'Use Microsoft Azure Storage for all media uploads on this site.', 'windows-azure-storage' );
	echo '</label>';

	echo '<p>';
		_e( 'Note: Uncheck this to store uploads on your web server by default. This setting can be overriden using the <code>MICROSOFT_AZURE_USE_FOR_DEFAULT_UPLOAD</code> PHP constant.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * Keep local file setting callback function.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_setting_keep_local_file() {
	?>
	<input type="checkbox" name="azure_storage_keep_local_file" title="<?php esc_attr_e( 'Do not delete local files after uploading them to Azure Storage.', 'windows-azure-storage' ) ?>" value="1" id="azure_storage_keep_local_file" <?php checked( (bool) get_option( 'azure_storage_keep_local_file' ) ); ?> />
	<label for="azure_storage_keep_local_file">
		<?php esc_html_e( 'Keep local files after uploading them to Azure Storage.', 'windows-azure-storage' ); ?>
	</label>
	<?php
}

/**
 * Browse cache results setting.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_browse_cache_results() {
	$ttl = Windows_Azure_Helper::get_cache_ttl();
	?>
	<input type="number" name="azure_browse_cache_results" class="regular-text" title="<?php esc_attr_e( 'Browse azure file list cache TTL', 'windows-azure-storage' ); ?>" value="<?php echo esc_attr( $ttl ); ?>"/>
	<p class="field-description">
		<?php
		echo __(
			'Note: If you want to disable azure file list caching please set this value to 0.',
			'windows-azure-storage'
		);
		?>
	</p>
	<?php
}

/**
 * Displays cache-control setting.
 *
 * @since 4.1.0
 *
 * @return void
 */
function windows_azure_cache_control() {
	$cache_control = Windows_Azure_Helper::get_cache_control();

	if ( defined( 'MICROSOFT_AZURE_CACHE_CONTROL' ) ) {
		echo '<input type="text" class="regular-text" value="', esc_attr( $cache_control ), '" readonly disabled>';
	} else {
		echo '<input type="text" name="azure_cache_control" class="regular-text" value="', esc_attr( $cache_control ), '">';
	}

	echo '<p>';
		_e( 'Setting Cache-Control on publicly accessible Microsoft Azure Blobs can help reduce bandwidth by preventing consumers from having to continuously download resources. Specify a relative amount of time in seconds to cache data after it was received or enter exact cache-control value which you want to use for your assets. You can define <code>MICROSOFT_AZURE_CACHE_CONTROL</code> constant to override it.', 'windows-azure-storage' );
	echo '</p>';
}

/**
 * Try to create a container.
 *
 * @since 4.0.0
 *
 * @param boolean $success True if the operation succeeded, false otherwise. Deprecated.
 *
 * @return string|WP_Error|null Success message or WP_Error on failure.
 */
function create_container_if_required( &$success = null ) {
	$success    = false;
	$post_array = wp_unslash( $_POST );
	$action_set = isset( $post_array['newcontainer'] ) && $permissions = current_user_can( 'manage_options' ) && $admin_referer = check_admin_referer( 'create_container', 'create_new_container_settings' );
	if ( $action_set ) {
		if ( ! empty( $post_array['newcontainer'] ) ) {
			if ( empty( $post_array['azure_storage_account_name'] ) || empty( $post_array['azure_storage_account_primary_access_key'] ) ) {
				return new WP_Error( -2, __( 'Please specify Storage Account Name and Primary Access Key to create container.', 'windows-azure-storage' ) );
			}

			try {
				$account_name = $post_array['azure_storage_account_name'];
				$account_key  = $post_array['azure_storage_account_primary_access_key'];
				$result       = Windows_Azure_Helper::create_container( sanitize_text_field( $post_array['newcontainer'] ), $account_name, $account_key );

				if ( ! is_wp_error( $result ) ) {
					return sprintf(
						__( 'The container <strong>%1$s</strong> successfully created. To use this container as default container, select it from the above drop down and click <strong>Save Changes</strong>.', 'windows-azure-storage' ),
						esc_html( $result )
					);
				} else {
					$success = true;

					return $result;
				}
			} catch ( Exception $e ) {
				return new WP_Error( -3, sprintf( __( 'Container creation failed, Error: %s', 'windows-azure-storage' ), $e->getMessage() ) );
			}
		}

		return new WP_Error( -4, __( 'Please specify name of the container to create', 'windows-azure-storage' ) );
	} elseif ( $action_set ) {
		$error_message = __( 'Unable to create new container. Try again.', 'windows-azure-storage' );
		if ( ! $permissions ) {
			$error_message = __( 'You do not have permissions to create new container.', 'windows-azure-storage' );
		} elseif ( ! $admin_referer ) {
			$error_message = __( 'Form validation failed. Try again.', 'windows-azure-storage' );
		}

		return new WP_Error( -1, $error_message );
	}

	return null;
}

/**
 * Action hook for load-settings_page_windows-azure-storage-plugin-options.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_load_settings_page() {
	$result = create_container_if_required();
	if ( null === $result ) {
		return;
	}
	add_action( 'admin_notices', function () use ( $result ) {
		if ( is_wp_error( $result ) ) {
			$notice_class = 'notice-error';
			$notice       = sprintf( __( 'Container creation failed. Error: %s', 'windows-azure-storage' ), $result->get_error_message() );
			add_filter( 'windows_azure_storage_container_creation_failed', '__return_true' );
		} elseif ( is_string( $result ) ) {
			$notice_class = 'notice-success';
			$notice       = $result;
		}
		?>
		<div class="notice <?php echo esc_attr( $notice_class ); ?> is-dismissible">
			<p><?php echo wp_kses( $notice, array( 'strong' => array() ) ); ?></p>
		</div>
		<?php
	} );
}

/**
 * Action hook for load-settings_page_windows-azure-storage-plugin-options.
 *
 * @since 4.0.0
 *
 * @return void
 */
function windows_azure_storage_check_container_access_policy() {
	if ( ! isset( $_REQUEST['settings-updated'] ) || 'true' !== $_REQUEST['settings-updated'] ) {
		return;
	}

	$container     = Windows_Azure_Helper::get_default_container();
	$container_acl = Windows_Azure_Helper::get_container_acl( $container );
	if ( Windows_Azure_Rest_Api_Client::CONTAINER_VISIBILITY_PRIVATE !== $container_acl ) {
		return;
	}
	add_action( 'admin_notices', function () use ( $container ) {
		$private_container_warning = sprintf(
			__(
				'Warning: The container <strong>%1$s</strong> is set to <strong>private</strong> and cannot be used. Please choose a public container as the default, or set the <strong>%1$s</strong> container to <strong>public</strong> in your Azure Storage settings.',
				'windows-azure-storage'
			),
			$container
		);
		?>
		<div class="notice notice-warning is-dismissible">
			<p><?php echo $private_container_warning; ?></p>
		</div>
		<?php
	} );
}
