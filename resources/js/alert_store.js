export default {
        show: false,
        variant: 'info',
        title: '',
        message: '',
        showLink: false,
        linkHref: '#',
        linkText: 'Learn more',
        duration: 3, // seconds

        showAlert(data = {}) {
            this.show = true;
            this.variant = data.variant || 'info';
            this.title = data.title || '';
            this.message = data.message || '';
            this.showLink = data.showLink || false;
            this.linkHref = data.linkHref || '#';
            this.linkText = data.linkText || 'Learn more';
            this.duration = data.duration ?? this.duration;

            if (this.duration > 0) {
                setTimeout(() => {
                    this.hideAlert();
                }, this.duration * 1000);
            }
        },

        hideAlert() {
            this.show = false;
        },

        success(message, title = '') {
            this.showAlert({
                variant: 'success',
                title: title,
                message: message
            });
        },

        error(message, title = '', duration = null) {
            this.showAlert({
                variant: 'error',
                title: title,
                message: message,
                duration: duration
            });
        },
        warning(message, title = '') {
            this.showAlert({
                variant: 'warning',
                title: title,
                message: message
            });
        },

        info(message, title = '') {
            this.showAlert({
                variant: 'info',
                title: title,
                message: message
            });
        }
    }

