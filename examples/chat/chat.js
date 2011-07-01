var Server = function(chatii, location) {
	var channel = new Channel(chatii, 'status');

	var interface = {
		// generic error handler
		error: function(message) {
			alert(message.text);
		},
		connect: function() {
			if(sessionStorage.username == undefined) {
				var _instance = this;
				jQuery.getJSON(location + 'server.php', {'action': 'connect'}, function(message) {
					_instance.onConnect(message);
				});
			} else {
				this.onConnect({
					'code': 200,
					'data': {
						'username': sessionStorage.username
					}
				})
			}
		},
		onConnect: function(message) {
			if(message.code < 200) {
				this.error(message);
			} else {
				sessionStorage.username = message.data.username;
				chatii.write('status', '<em>Welcome ' + message.data.username + '</em>');
			}
		},
		send: function(message) {
			var data = {
				'action': 'message',
				'channel': chatii.active(),
				'username': sessionStorage.username,
				'message': message
			}
			var _instance = this;
			jQuery.getJSON(location + 'server.php', data, function(message) {
				_instance.onSend(message);
			});
		},
		onSend: function(message) {
			if(message.code < 200) {
				this.error(message);
			} else {
				switch( message.data.action ) {
					case 'add-channel':
						var channel = new PollChannel(chatii, message.data.name);
						chatii.write(message.data.name, '<em>' + sessionStorage.username + ' has joined ' + message.data.name + '</em>');
						break;
				}
			}
		}
	}
	return interface;
}

function timestamp() {
	var now = new Date()
	var seconds = now.getSeconds();
	if( (''+seconds).length < 2 )
		seconds = '0' + seconds;
	var hours = now.getHours();
	if( (''+seconds).length < 2 )
		hours = '0' + hours;
	return '[' + hours + ':' + seconds + ']';
}

function writeline(name, message) {
	var time = timestamp();
	var lines = [
		'<div class="line">',
			'<div class="timestamp">', time,'</div>',
			'<div class="text">', message,'</div>',
		'</div>'
	];
	$('div#' + name).append(lines.join(''))
}

var PollChannel = function(chatii, name) {
	$('ul', chatii.container()).append($('<li><a href="#'+name+'">'+name+'</a></li>'));
	$('#input-container', chatii.container()).before($('<div class="tab" id="'+name+'"></div>'));
	$('#' + name).click(function() {
		$('#input-container input')[0].focus();
	});

	var interface = {
		write: function(message) {
			writeline(name, message);
		}
	}
	
	var data = {
		'action': 'subscribe',
		'channel': name,
		'username': sessionStorage.username
	};

	
	var poll = new LongPoll('server.php', data, function(update) {
		writeline(update.data[1].replace('room:', ''), update.data[2]);
		// keep scrolled to bottom
		var div = document.getElementById(update.data[1].replace('room:', ''));
		div.scrollTop = div.scrollHeight;		
	});

	chatii.register(name, interface);
	chatii.reload(-1);
	return interface;
}

var Channel = function(chatii, name) {
	$('ul', chatii.container()).append($('<li><a href="#'+name+'">'+name+'</a></li>'));
	$('#input-container', chatii.container()).before($('<div class="tab" id="'+name+'"></div>'));
	$('#' + name).click(function() {
		$('#input-container input')[0].focus();
	});
	
	var interface = {
		write: function(message) {
			writeline(name, message);
		}
	}
	chatii.register(name, interface);
	chatii.reload(-1);
	return interface;
}

var Chatii = function(container) {
	var version = '0.1a';
	var server = null;
	var channels = {};
	var index = 0;
	var text = [];

	function size(obj) {
		var size = 0, key;
		for (key in obj) {
			if (obj.hasOwnProperty(key)) size++;
		}
		return size;
	};

	var interface = {
		init: function() {
			server = new Server(this, document.location.href);
			this.write('status', '<em>chatii ' + version + '</em>');
			server.connect();
			var _instance = this;
			$('#keyboard-input').keydown(function(e) {
				if( e.keyCode == 38 ) {
					$(e.target).val(text.pop());
				}
			});
			$('#input-form').submit(function(e) {
				e.preventDefault();
				var message = $('input', e.target).val();
				$('input', e.target).val('');
				text.push(message);
				server.send(message);
			});
			//server.send('/join #chat');
			writeline('status', '');
			writeline('status', 'Syntax:');
			writeline('status', "&nbsp;&nbsp;&nbsp; /join #&lt;channel&gt; - join channel");
			writeline('status', "&nbsp;&nbsp;&nbsp; all other text is sent as a message to the currently active channel");
			writeline('status', '');
			writeline('status', '<em><strong>*note*</strong> this, the #status window is not meant for messaging and does not support it</em>');
			writeline('status', '<em><strong>*note*</strong> this chat system relies on sessionStorage and therefore each browser window will get a new username</em>');
			$('input', '#keyboard-input').focus();
		},
		// register channel under name and reload the tabs 
		register: function(name, channel) {
			channels[name] = channel;
		},
		// reload the tabs and optionally switch to tab index,
		//	index = 0, 1, .. 
		//		switch to tab index (default = 1, the status tab)
		//	index = -1
		//		switch to rightmost tab
		reload: function(index) {
			if( index == undefined )
				index = 1
			if( index == -1 )
				index = size(channels);
			new Yetii({
				id: container.replace('#', ''),
				active: index
			});	
		},
		write: function(channel, message) {
			if( !(channel in channels) ) {
				alert('trying to write in an unknown channel');
			} else {
				channels[channel].write(message);
			}
		},
		// return the container
		container: function() {
			return $(container);
		},
		// return active channel name
		active: function() {
			return $('a.active', container).html();
		}
	}
	interface.init();
	return interface;
}

$(function() {
	var chatii = new Chatii('#tab-container');
})

var LongPoll = function(url, data, func) {
	jQuery.ajaxTransport('longpoll', function(s) {
		// points to last char of last response so we can read the new data
		var length = 0;
		// return an object for jQuery
		return {
			send: function(headers, completeCallback) {
				var xhr = s.xhr();
				xhr.onreadystatechange = function(event) {
					var message = xhr.responseText.substr(length);
					message = message.replace(/^\s\s*/, '').replace(/\s\s*$/, '');
					if( message != '' ) {					
						var obj = eval('(' + message + ')');
						func(obj);
						length = xhr.responseText.length;
					}
				};
				xhr.open(s.type, s.url, s.async);
				xhr.send((s.hasContent && s.data) || null);
			},
			abort: function() {
				xhr.abort();
			}
		}
	});
	
	var handler = function() {
		$.ajax(url, {
			data: data,
			cache: 'false',
			dataType: 'longpoll',
			error: function(data, textStatus, xhr) {
				handler();
			}
		});
	}

	var _instance = this;
	setTimeout(function() {
		handler.call(_instance)
	}, 500);
}