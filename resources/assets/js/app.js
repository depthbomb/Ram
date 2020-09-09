import $ from 'jquery';
window.jQuery = $;
window.$ = $;

import './vendor/semantic.min';

window.Ram = new function() {
	const self = this;
	const configElement = document.querySelector('meta[name="application\/config"]');

	this.Config = JSON.parse(configElement.getAttribute('content'));
	this.UI     = {};
	this.Utils  = {};
	this.Init   = function() {
		const csrfToken = self.Config.csrfToken;
		if (csrfToken) {
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': csrfToken
				}
			});
		} else {
			throw new Error('CSRF token not found');
		}

		$('.ui.dropdown').dropdown();

		self.Routing.Init(self.Config.routeName);
		self.Events.Init();
		self.Clicks.Init();
	};
};
Ram.Routing = new function() {
	this.Init = function(route) {
		switch (route) {
			case 'index': {
				
			} case 'map.index': {
				const search = document.querySelector('.ui.search.selection.dropdown');
				const input  = search.querySelector('input[type=hidden][name=map]');
				const text   = search.querySelector('.text');
				const submit = document.querySelector('button.ui.submit.button');

				input.value = '';
				submit.addEventListener('click', e => {
					const map = input.value.trim();
					if (map !== '') {
						search.classList.add('disabled', 'elastic', 'teal', 'loading');
						search.setAttribute('disabled', 'disabled');
						submit.classList.add('disabled');
						submit.setAttribute('disabled', 'disabled');

						Ram.Ajax.Request('map/setnextmap', { map }, (data) => {
							search.remove();
							submit.remove();
							
							$('body').toast({
								class: 'success',
								message: `The next map has been set to ${map}!`,
								showProgress: 'bottom'
							});
						}, (data) => {
							text.classList.add('default');
							text.innerHTML = 'Select a map';
							input.value = '';
							search.classList.remove('disabled', 'elastic', 'teal', 'loading');
							search.removeAttribute('disabled');
							submit.classList.remove('disabled');
							submit.removeAttribute('disabled');

							$('body').toast({
								class: 'error',
								message: data.message,
								showProgress: 'bottom'
							});
						});
					}
				});
			}
		}
	};
};
Ram.Events = new function() {
	this.Init = function() {
		
	};
};
Ram.Clicks = new function() {
	this.Init = function() {
		document.body.addEventListener('click', e => {
			const target = e.target || e.srcElement;
			const currentTarget = e.currentTarget;
			const targetTag = target.tagName;
			if (target.matches('time')) {
				const toggleData = target.dataset.time;
				const oldData = target.textContent;
				if (toggleData) {
					target.textContent = toggleData;
					target.dataset.time = oldData;
				}
			}
		}, true);
	};

	
}
Ram.Ajax = new function() {
	const self = this;
	this.baseUrl = '/ajax';
	this.Request = function(endpoint, params, callback, failCallback, onComplete) {
		callback = callback || function (data) {
			if (data.message) alert(data.message);
		};
		failCallback = failCallback || function (data) {
			alert(data.message || "Unknown Error");
		};
		onComplete = onComplete || function () {};
		params = params || {};
		return $.ajax({
			type: "POST",
			url: `${self.baseUrl}/${endpoint}`,
			data: params,
			dataType: 'json',
			success: (data) => {
				if (data.success) {
					if (data.redirectLink) window.location = data.redirectLink;
					if (data.reloadPage) location.reload();
					callback(data);
				} else {
					failCallback(data);
				}
			},
			error: (jqXHR, textStatus, errorThrown) => {
				console.log(jqXHR);
				console.log(textStatus);
				console.log(errorThrown);
				const data = {
					message: errorThrown || textStatus
				};
				failCallback(data);
			},
			complete: (jqXHR, textStatus) => {
				onComplete();
			}
		});
	};
};
Ram.Utils.ShuffleArray = function(array) {
	let currentIndex = array.length, temporaryValue, randomIndex;
	while (0 !== currentIndex) {
		randomIndex = Math.floor(Math.random() * currentIndex);
		currentIndex -= 1;

		temporaryValue = array[currentIndex];
		array[currentIndex] = array[randomIndex];
		array[randomIndex] = temporaryValue;
	}
	return array;
};
Ram.Utils.MatchRule = function(str, rule) {
	return new RegExp("^" + rule.split("*").join(".*") + "$").test(str);
};
$(Ram.Init);