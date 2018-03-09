/*
 * jq-upload.js - Jquery File Uploader - 0.0.1
 */

(($) => {


var pluginName = 'jq-upload';

var defaults = {
	url: document.URL,
	method: 'POST',
	getData: () => {},
	data: {},
	allowedTypes: '*',
	extFilter: null,
	// dataType: null,
	dataType: 'text',
	fileName: 'file',
	checkForFile: true,
	onInit: () => {},
	onBadBrowser: (message) => {},
	onBeforeUpload: () => {},
	onComplete: () => {},
	onUploadProgress: (percent) => {},
	onUploadSuccess: (data) => {},
	onUploadError: (message) => {},
	onHaventFile: () => {},
	onFileTypeError: (file) => {},
	onFileExtError: (file) => {}
};


var jqUpload = function(element, options) {
	self = this;
	self.file;
	self.haveData = false;
	self.element = $(element);

	self.settings = $.extend({}, defaults, options);

	if (!self.checkBrowser()) return false;

	self.init();
	return true;
};




jqUpload.prototype.checkBrowser = () => {
	if (window.FormData === undefined) {
		self.settings.onBadBrowser.call(self.element, 'Browser doesn\'t support Form API');

		return false;
	}

	if (self.element.find('input[type=file]').length > 0) {
		return true;
	}

	return true;
};



jqUpload.prototype.init = () => {
	self.element.find('input[type=file]').on('change', (e) => {
		self.checkFile(e.target.files);
	});

	self.settings.onInit.call(self.element);

	self.element.on('submit', (e) => {
		e.preventDefault();

		if (self.settings.checkForFile) {
			if (self.element.find('[type=file]')[0].files.length > 0) {
				self.ajax();
			} else {
				self.settings.onHaventFile.call(self.element);
			}
		} else {
			self.getFormData();
			self.ajax();
		}
	});
};



jqUpload.prototype.checkFile = (files) => {
	var file = files[0];

	// Check file type
	if ((self.settings.allowedTypes != '*') &&
			!file.type.match(self.settings.allowedTypes)) {

		self.settings.onFileTypeError.call(self.element, file);

		return false;
	}

	// Check file extension
	if (self.settings.extFilter != null) {
		var extList = self.settings.extFilter.toLowerCase().split(';');

		var ext = file.name.toLowerCase().split('.').pop();

		if ($.inArray(ext, extList) < 0) {
			self.settings.onFileExtError.call(self.element, file);

			return false;
		}
	}

	self.file = file;
	self.getFormData();

	return true;
};



jqUpload.prototype.getFormData = () => {
	self.fd = new FormData();
	self.fd.append(self.settings.fileName, self.file);
};



jqUpload.prototype.ajax = () => {
	self.settings.onBeforeUpload.call(self.element);

	// Dinamic extra data
	self.settings.data = self.settings.getData();
	
	// Append extra Form Data
	$.each(self.settings.data, (key, val) => {
		self.fd.append(key, val);
	});


	// Make ajax request to server
	$.ajax({
		url: self.settings.url,
		type: self.settings.method,
		dataType: self.settings.dataType,
		data: self.fd,
		cache: false,
		contentType: false,
		processData: false,
		forceSync: false,
		xhr: () => {
			// console.log(self.settings.data, self.fd);
			var xhrobj = $.ajaxSettings.xhr();

			if (xhrobj.upload) {
				xhrobj.upload.addEventListener('progress', (e) => {

					var percent = 0;
					var position = e.loaded || e.position;
					var total = e.total || e.totalSize;

					if (e.lengthComputable) {
						percent = Math.ceil(position / total * 100);
					}

					self.settings.onUploadProgress.call(self.element, percent);
				}, false);
			}

			return xhrobj;
		},
		success: (data, message, xhr) => {
			self.settings.onUploadSuccess.call(self.element, data);
			delete self.file;
			// delete self.fd;
		},
		error: (xhr, status, errMsg) => {
			self.settings.onUploadError.call(self.element, errMsg);
		},
		complete: (xhr, textStatus) => {
			self.settings.onComplete.call(self.element);
		}
	});
};




$.fn.jqUpload = function(options) {
	return this.each(() => {
		if (!$.data(this, pluginName)) {
			$.data(this, pluginName, new jqUpload(this, options));
		}
	});
};



})(jQuery);