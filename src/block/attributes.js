export const attributes = {
	numberCategories: {
		type: 'number',
		default: 4,
	},
	blockTitle: {
		type: 'array',
		source: 'children',
		selector: '.heather-block-title',
	},
};
