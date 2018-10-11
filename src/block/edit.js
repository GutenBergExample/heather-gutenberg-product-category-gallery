const { Component } = wp.element;

const { InspectorControls, RichText } = wp.editor;
const { RangeControl } = wp.components;

const { __ } = wp.i18n;

const { Spinner } = wp.components;

export default class HeatherEdit extends Component {
	state = {
		categories: [],
	};

	onChangeNumber = numberCategories => {
		this.props.setAttributes( {
			numberCategories,
		} );

		this.fetchResults( numberCategories );
	};

	componentDidMount() {
		this.fetchResults();
	}

	fetchResults = number => {
		number = number ? number : this.props.attributes.numberCategories;

		fetch( `/wp-json/heather/v1/getCategories/number/${ number }` )
			.then( results => {
				return results.json();
			} )
			.then( data => {
				this.setState( {
					categories: data.categories,
				} );
			} );
	};

	render() {
		const {
			attributes: { numberCategories, blockTitle },
			className,
		} = this.props;

		const { categories } = this.state;

		if ( ! categories ) {
			return (
				<p className={ className }>
					<Spinner />
					{ __( 'Loading product categories' ) }
				</p>
			);
		}
		if ( 0 === categories.length ) {
			return <p>{ __( 'No product categories' ) }</p>;
		}

		return (
			<div className={ className }>
				<InspectorControls>
					<RangeControl
						value={ numberCategories }
						onChange={ this.onChangeNumber }
						min={ 1 }
						max={ 20 }
						step={ 1 }
						allowReset="true"
						label={ __( 'Number of categories' ) }
					/>
				</InspectorControls>
				<ul>
					<RichText
						tagName="h6"
						className="heather-block-title"
						value={ blockTitle }
						onChange={ blockTitle => setAttributes( { blockTitle } ) }
						placeholder={ __( 'Browse our products' ) }
					/>
					{ categories &&
						categories.map( category => {
							return <li key={ category.id }>{ category.name }</li>;
						} ) }
				</ul>
				<div className="heather-scroller">
					<img
						alt={ categories[ 0 ].name }
						key={ categories[ 0 ].id }
						src={ categories[ 0 ].image }
					/>
				</div>
			</div>
		);
	}
}
