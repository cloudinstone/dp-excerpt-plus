/* global dpExcerptSettings */

import './admin.scss';

import domReady from '@wordpress/dom-ready';

import api from '@wordpress/api';

import { __ } from '@wordpress/i18n';

import {
	Button,
	ExternalLink,
	Panel,
	PanelBody,
	SelectControl,
	SnackbarList,
	CheckboxControl,
	FormTokenField,
	BaseControl,
	TextControl,
	__experimentalText as Text,
	__experimentalNumberControl as NumberControl,
	__experimentalHStack as HStack
} from '@wordpress/components';

import { dispatch, useDispatch, useSelect } from '@wordpress/data';

import { Fragment, render, Component, useState } from '@wordpress/element';

import { store as noticesStore } from '@wordpress/notices';

import _ from 'lodash';

const Notices = () => {
	const notices = useSelect(
		(select) =>
			select(noticesStore)
				.getNotices()
				.filter((notice) => {
					return notice.type === 'snackbar';
				}),
		[]
	);

	const { removeNotice } = useDispatch(noticesStore);

	return (
		<SnackbarList
			className="dp-excerpt-settings-notices"
			notices={notices}
			onRemove={removeNotice}
		/>
	);
};

class SettingsPage extends Component {
	constructor() {
		super(...arguments);

		this.state = dpExcerptSettings;
		console.log(dpExcerptSettings);
		this.state.isSaving = false;
	}

	render() {
		const {
			length,
			length_type,
			trim_marker,
			isSaving
		} = this.state;

		return (
			<Fragment>
				<div className="dp-admin-page-header">
					<div className="dp-admin-page-header-container">
						<h1 className="dp-admin-page-title">
							{__('Excerpt Settings', 'dp-excerpt-plus')}
						</h1>
					</div>
				</div>

				<div className="dp-admin-page-body">
					<div className="dp-admin-page-content">
						<Panel>
							<PanelBody
								initialOpen={true}
								title={__('General', 'dp-excerpt-plus')}
							>
								<HStack
									justify="flex-start"
									alignment="bottom"
									style={{ marginBottom: '8px' }}
								>
									<BaseControl>
										<NumberControl
											label={__('Excerpt length', 'dp-excerpt-plus')}
											labelPosition="side"
											value={length}
											isShiftStepEnabled={true}
											shiftStep={10}
											onChange={(length) => this.setState({ length })}
											style={{ width: "6em" }}
										/>
									</BaseControl>
									<SelectControl
										label={__('Excerpt length type', 'dp-excerpt-plus')}
										hideLabelFromVision={true}
										value={length_type}
										options={[
											{ label: __('Words', 'tdomain'), value: 'words' },
											{ label: __('Characters', 'tdomain'), value: 'chars' }
										]}
										onChange={(length_type) => this.setState({ length_type })}
									/>
								</HStack>

								<TextControl
									label={(
										<>
											<Text>{__('Trim marker', 'dp-excerpt-plus')}</Text>
											<ExternalLink
												href="http://entitycode.com/"
												style={{ marginLeft: '8px' }}>
												{__('Use HTML entities', 'dp-excerpt-plus')}
											</ExternalLink>
										</>
									)}
									help={__('A marker that is added to the end of excerpt when excerpt is truncated.', 'dp-excerpt-plus')}
									value={trim_marker}
									onChange={(trim_marker) => this.setState({ trim_marker })}
								/>
							</PanelBody>
						</Panel>

						<p className="dp-admin-page-save-settings">
							<Button
								isPrimary
								isBusy={isSaving}
								onClick={() => {
									this.setState({ isSaving: true });

									let settings = new api.models.Settings({
										dp_excerpt_settings: {
											length,
											length_type,
											trim_marker,
										},
									});

									settings
										.save()
										.then((response) => {
											console.log(response);
											this.setState({ ...response.dp_excerpt_settings, isSaving: false });

											dispatch(
												'core/notices'
											).createNotice(
												'success',
												__(
													__('Settings saved.', 'tdomain'),
													'dp-excerpt-plus'
												),
												{
													type: 'snackbar',
													isDismissible: true,
													id: 'save-settings-notice',
													actions: [
														{
															url: '/',
															label: __(
																__('View site', 'tdomain')
															),
														},
													],
												}
											);
										})
										.catch((error) => {
											// TODO: Catch error and display for better user experience.
											console.error('Error:', error);
										});
								}}
							>
								{isSaving
									? __('Saving', 'dp-excerpt-plus')
									: __('Save Settings', 'dp-excerpt-plus')}
							</Button>
						</p>
					</div>
					<Notices />
				</div>
			</Fragment>
		);
	}
}

domReady(() => {
	const pageWrap = document.getElementById('dp-excerpt-settings-page-wrap');

	if (pageWrap) {
		render(<SettingsPage />, pageWrap);
	}
});
