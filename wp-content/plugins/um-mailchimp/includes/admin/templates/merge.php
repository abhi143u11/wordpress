<div class="um-admin-metabox">

	<?php $fields = array();

		$list_id = get_post_meta( get_the_ID(), '_um_list', true );
		$merged = get_post_meta( get_the_ID(), '_um_merge', true );
		$merge_vars = UM()->Mailchimp_API()->api()->get_vars( $list_id );
		$options = array( '0' => __( 'Ignore this field', 'um-mailchimp' ) );
		foreach ($merge_vars as $k => $var) {
			if (!isset( $var['tag'] ))
				continue;

			$options[$var['tag']] = $var['name'];
		}
		foreach (UM()->builtin()->all_user_fields() as $key => $arr) {

			if (isset( $arr['title'] )) {
				$value = '0';
				$fields[] = array(
					'id'      => $key,
					'type'    => 'select',
					'size'    => 'medium',
					'label'   => $arr['title'],
					'value'   => ( $merged && isset( $merged[$key] ) ) ? $merged[$key] : '0',
					'options' => $options,
				);
			}

		}
		UM()->admin_forms( array(
			'class'  => 'um-form-mailchimp-merge um-half-column',
			'fields' => $fields,
            'prefix_id'=> 'mailchimp[_um_merge]'
		) )->render_form(); ?>

    <div class="um-admin-clear"></div>
</div>