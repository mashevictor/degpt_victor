const theGroups = document.querySelectorAll( '.mk-setting-group' );

theGroups.forEach( ( group ) => {
	const updateInput = group.querySelector( '.mk-items-value-input' );
	const itemsList = group.querySelector( '.mk-items-list' );
	const addBtn = group.querySelector( '.mk-add-item-btn' );

	const inputEvent = [];
	const deleteEvent = [];

	const onChange = () => {
		const allItem = itemsList.querySelectorAll( '.mk-opt-item' );
		let theValue = [];
		allItem.forEach( ( item ) => {
			const theInput = item.querySelector( '.mk-item-input' );
			if ( theInput.value ) {
				theValue.push( theInput.value );
			}
		} );
		theValue = theValue.join( '@mk&' );
		updateInput.value = theValue;
	};

	const onDelete = ( e ) => {
		const allItem = itemsList.querySelectorAll( '.mk-opt-item' );
		allItem.forEach( ( item, index ) => {
			const theInput = item.querySelector( '.mk-item-input' );
			const theDelete = item.querySelector( '.mk-item-delete' );
			if ( inputEvent[ index ] ) {
				theInput.removeEventListener( 'click', inputEvent[ index ] );
			}
			if ( deleteEvent[ index ] ) {
				theDelete.removeEventListener( 'click', deleteEvent[ index ] );
			}
		} );
		e.target.parentNode.remove();
		const newItem = itemsList.querySelectorAll( '.mk-opt-item' );
		let theValue = [];
		newItem.forEach( ( item, index ) => {
			const theInput = item.querySelector( '.mk-item-input' );
			const theDelete = item.querySelector( '.mk-item-delete' );
			inputEvent[ index ] = () => onChange();
			theInput.addEventListener( 'keyup', inputEvent[ index ] );
			deleteEvent[ index ] = ( event ) => onDelete( event );
			theDelete.addEventListener( 'click', deleteEvent[ index ] );
			if ( theInput.value ) {
				theValue.push( theInput.value );
			}
		} );
		theValue = theValue.join( '@mk&' );
		updateInput.value = theValue;
	};

	const addItem = () => {
		const newItem =
			'<div class="mk-opt-item"><input type="text" class="mk-item-input" value/><span class="mk-item-delete">删除</span></div>';
		itemsList.insertAdjacentHTML( 'beforeend', newItem );
		const allItem = itemsList.querySelectorAll( '.mk-opt-item' );
		allItem.forEach( ( item, index ) => {
			const theInput = item.querySelector( '.mk-item-input' );
			const theDelete = item.querySelector( '.mk-item-delete' );
			if ( inputEvent[ index ] ) {
				theInput.removeEventListener( 'keyup', inputEvent[ index ] );
			}
			inputEvent[ index ] = () => onChange();
			theInput.addEventListener( 'keyup', inputEvent[ index ] );
			if ( deleteEvent[ index ] ) {
				theDelete.removeEventListener( 'click', deleteEvent[ index ] );
			}
			deleteEvent[ index ] = ( e ) => onDelete( e );
			theDelete.addEventListener( 'click', deleteEvent[ index ] );
		} );
	};

	addBtn.addEventListener( 'click', addItem );

	if ( updateInput.value ) {
		const theArray = updateInput.value.split( '@mk&' );
		theArray.forEach( ( ele ) => {
			const newItem = `<div class="mk-opt-item"><input type="text" class="mk-item-input" value="${ ele }"/><span class="mk-item-delete">删除</span></div>`;
			itemsList.insertAdjacentHTML( 'beforeend', newItem );
		} );
		const allItem = itemsList.querySelectorAll( '.mk-opt-item' );
		allItem.forEach( ( item, index ) => {
			const theInput = item.querySelector( '.mk-item-input' );
			const theDelete = item.querySelector( '.mk-item-delete' );
			if ( inputEvent[ index ] ) {
				theInput.removeEventListener( 'click', inputEvent[ index ] );
			}
			inputEvent[ index ] = () => onChange();
			theInput.addEventListener( 'keyup', inputEvent[ index ] );
			if ( deleteEvent[ index ] ) {
				theDelete.removeEventListener( 'click', onDelete );
			}
			deleteEvent[ index ] = ( e ) => onDelete( e );
			theDelete.addEventListener( 'click', deleteEvent[ index ] );
		} );
	}
} );
