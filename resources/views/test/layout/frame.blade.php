<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="csrf-token" content="{{ csrf_token() }}">
		<title>{{ config('app.name', 'Laravel') }}</title>
		<link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
		<link href="{{ asset('css/typeahead.css') }}" rel="stylesheet">
		<link href="{{ asset('css/fontawesome.min.css') }}" rel="stylesheet">
		<script src="{{ asset('js/jquery.min.js') }}"></script>
		<script src="{{ asset('js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('js/typeahead.js') }}"></script>
		<style>
		.image-block {
			display: inline-block;
			position: relative;
			margin-right: 15px;
		}
		.image-block .remove-image-block {
			display: block;
			position: absolute;
			top: 5px;
			right: 5px;
		}
		.image-block:hover .remove-image-block {
			color: red;
		}
		</style>
	</head>
	<body>
		<div class="container mb-3">
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<a class="navbar-brand" href="#">Ik heb een opdracht.nl</a>
				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarSupportedContent">
					<ul class="navbar-nav mr-auto">
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="service_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Diensten
							</a>
							<div class="dropdown-menu" aria-labelledby="service_dropdown">
								<a class="dropdown-item" href="/service/overview">Overzicht</a>
								<a class="dropdown-item" href="/service/create">Dienst aanmaken</a>
							</div>
						</li>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="#" id="ad_dropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Advertenties
							</a>
							<div class="dropdown-menu" aria-labelledby="ad_dropdown">
								<a class="dropdown-item" href="/ad/overview">Overzicht</a>
								<a class="dropdown-item" href="/ad/create">Advertentie aanmaken</a>
							</div>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="/admin/categories" role="button">
								Categorieen
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
		
		@yield('content')
	</body>
</html>
<script>
var bloodhound_categories = new Bloodhound({
	datumTokenizer: function (row) {return Bloodhound.tokenizers.whitespace(row.title);},
	queryTokenizer: Bloodhound.tokenizers.whitespace,
	sorter: function(a, b) {
		return a.title.length - b.title.length;
	},
	prefetch: {
		url: '/category/json_categories',
		transform: function(result) {return result.values;},
		cache: false
	},
	identify: function(row) {return row.id;},
	limit: 10
});


function ids_from_string(str) {
	var list = [], str = ('' + str).split(','), i;
	for (i in str) {
		if (str[i] != '' && str[i] != '0' && str[i].match(/^[0-9]+$/)) list.push(str[i]);
	}
	return list;
}

$(window).ready(function() {
	add_dom_actions($('html'));
});

function add_dom_actions(html) {
	html.find('.category-widget').each(function() {
		var node = $(this), target = html.find(node.attr('target'));
		set_typeahead($(this), {
			dataset: bloodhound_categories,
			field: 'title',
			with_default: 'category',
			on_select_multi: true,
			get_ids: function() {
				return target.find('[type="checkbox"],[type="hidden"]').map(function() {return $(this).val();}).get();
			},
			on_select: function(obj, rows, is_auto) {
				console.log(rows);
				var i, selection = target.find('[type="checkbox"],[type="hidden"]').map(function() {return $(this).val();}).get();
				for (i in rows) {
					row = rows[i];
					if (selection.indexOf(row.id) > -1) return;
					if (node.attr('filter-mode') == 'checkboxes') {
						temp = $('<div key="' + node.attr('key') + '" class="checkbox-row checked"><input type="checkbox" name="' + node.attr('key') + '[]" value="' + row.id + '" class="node-checkbox" style="display: none;" checked="checked"><i class="far fa-check-square checked checkbox-icon"></i><i class="far fa-square not-checked checkbox-icon"></i> ' + row.title + '</div>');
						add_dom_actions(temp);
						target.append(temp);
						node.closest('form').trigger('field-change');
						node.typeahead('val', '');
					}
					else if (node.attr('filter-mode') == 'buttons') {
						temp = $('<div key="' + node.attr('key') + '" class="btn btn-info mr-1" style="' + (row.background_color ? 'background-color: ' + row.background_color + ';' : '') + (row.border_color ? 'border-color: ' + row.border_color + ';' : '') + '"><input type="hidden" name="' + node.attr('key') + '[]" value="' + row.id + '">' + (row.short_title ? row.short_title : row.title) + ' <i class="fas fa-times remove-dataset-item"></i></div>');
						add_dom_actions(temp);
						target.append(temp);
						node.closest('form').trigger('field-change');
						node.typeahead('val', '');
					}
					else if (node.attr('filter-mode') == 'single-button') {
						temp = $('<div key="' + node.attr('key') + '" class="btn btn-block btn-success dataset-button"><input type="hidden" name="' + node.attr('key') + '[]" value="' + row.id + '">' + row.title + ' <i class="fas fa-times remove-dataset-item"></i></div>');
						add_dom_actions(temp);
						target.empty();
						target.append(temp);
						node.closest('form').trigger('field-change');
						node.closest('.col, .inline-edit-cell, td').find('.typeahead-box').hide();
						node.typeahead('val', '');
					}
				}
			},
			empty: function() {
				target.empty();
			}
		});
	});
	
	html.find('.remove-dataset-item').click(function() {
		var col = $(this).closest('.col, .inline-edit-cell, td');
		$(this).closest('.btn').remove();
		col.find('.typeahead-box').show();
		col.closest('form').trigger('field-change');
	});
};


function last_used(key, id) {
	if (Storage) {
		var temp = [], i, ids = localStorage.getItem('glob.' + key) ? localStorage.getItem('glob.' + key).split(',') : [];
		if (id) {
			temp.push(id);
			for (i = 0; i < ids.length; i++) {
				if (ids[i] != id) temp.push(ids[i]);
				if (temp.length >= 10) break;
			}
			ids = temp;
			localStorage.setItem('glob.' + key, ids.join(','));
		}
		
		return ids;
	}
}

function set_typeahead(node, opt) {
	
	var with_default = opt.with_default ? function(q, sync) {
		if (q === '') {
			sync(opt.dataset.get(last_used(opt.with_default)));
		}
		else {
			opt.dataset.search(q, sync);
		}
	} : opt.dataset;
	
	var typeahead_set = false, init_typeahead = function() {
		
		if (typeahead_set) return false;
		typeahead_set = true;
		
		node.typeahead({
			limit: 10,
			hint: true,
			highlight: true,
			minLength: 0
		}, {
			source: with_default,
			display: opt.field,
			limit: 15
		});
		
		node.parent().css('display', 'block');
		if (ids.val().match(/[a-zA-Z]/)) node.typeahead('val', ids.val());
	}
	
	var i, rows, ids = $('<input type="hidden" name="' + node.attr('name') + '" value="' + (node.attr('value') ? node.attr('value') : '') + '">');
	node.attr('name', node.attr('default-name'));
	if (!ids.val().match(/[a-zA-Z]/)) node.attr('value', '');
	node.after(ids);
	node.on('typeahead:selected', function(obj, row, is_auto) {
		
		var rows, multi = false;
		if (typeof(row) == 'string') {
			rows = opt.dataset.get(ids_from_string(row));
			row = rows[0];
			multi = true;
		}
		else {
			rows = [row];
		}
		
		if (opt.on_select) {
			opt.on_select(obj, opt.on_select_multi || multi ? rows : row, is_auto);
			return;
		}
		
		var i, title = [], id = [];
		for (i = 0; i < rows.length; i++) {
			title.push(rows[i][opt.field]);
			id.push(rows[i].id);
		}
		title = title.join(', ');
		id = id.join(',');
		
		
		var label = $('<div class="input-group autocomplete_single_result"><input type="text" class="form-control is-disabled" value="' + title + '" title="' + title + '"><span class="input-group-addon"><i class="fa fa-close"></i></span></div>');
		
		if (typeahead_set) {
			node.parent().parent().find('.autocomplete_single_result').remove();
			node.parent().hide();
			node.parent().before(label);
		}
		else {
			node.parent().find('.autocomplete_single_result').remove();
			node.hide();
			node.before(label);
		}	
		ids.val(id);
		node.attr('selected-ids', id);
		label.click(function() {
			label.remove();
			if (typeahead_set) {
				node.parent().show();
				node.typeahead('val', '');
			}
			else {
				node.show();
				init_typeahead();
				node.typeahead('val', '');
			}
			node.focus();
			ids.val('');
			node.trigger('typeahead-change', [false]);
		});
		if (!is_auto && rows.length) {
			if (opt.with_default) last_used(opt.with_default, row.id);
			node.trigger('typeahead-change', [id]);
		}
	});
	
	var row_ids = ids_from_string(ids.val()), tries = 0, try_init = function() {
		var rows = opt.dataset.get(row_ids);
		if (rows.length) {
			for (i in rows) {
				node.trigger('typeahead:selected', [rows[i], true]);
			}
		}
		else {
			if (tries++ < 100) setTimeout(try_init, 100);
		}
	};
	
	node.on('select-id', function(obj, id) {
		node.trigger('typeahead:selected', [id + ''], true);
		node.trigger('typeahead-change', [false]);
	});
	
	node.closest('.input-group').find('.typeahead-show-full-list').click(function() {
		var i, options = opt.dataset.all(), selected_ids = opt.get_ids ? opt.get_ids() : ids_from_string(ids.val());
		content = $('<form></form>');
		options.sort(function(a, b) {return a.title > b.title;});
		for (i in options) {
			content.append('<label><input type="checkbox" ' + (selected_ids.indexOf(options[i].id) != -1 ? 'checked' : '') + ' name="selected-ids[]" value="' + options[i].id + '"> ' + options[i].title + '</label><br>');
		}
		add_dom_actions(content);
		
		Dialog.info('Selecteer velden', content, {
			callback: function() {
				if (opt.empty) opt.empty();
				node.trigger('typeahead:selected', [content.find('[name="selected-ids[]"]:checked').map(function() {return this.value;}).get().join(',')]);
			}
		});
	});
	
	if (row_ids.length) {
		try_init();
	}
	
	if (opt.delay_init) {
		node.mouseover(function() {
			init_typeahead();
		});
		node.focus(function() {
			init_typeahead();
		});
	}
	else {
		init_typeahead();
	}
}
</script>
