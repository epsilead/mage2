/**
 * AR_Epistolary module
 *
 *
 * @category  AR
 * @package   AR_Epistolary
 * @copyright 2018 Artem Rotmistrenko
 * @license
 * @author    Artem Rotmistrenko
 */
require(['jquery', 'Magento_Ui/js/modal/modal', '!domReady'], function($) {
    var sendMail = {
        button: $('#mail'),
        popup: $('#modal-overlay'),

        init: function() {
            this.showModal();
            this.bindUIActions();
        },

        showModal: function() {
            var self = this;
            this.popup.modal({
                buttons: [{
                    text: 'Mail',
                    class: 'send',
                    click: self.sendForm
                },
                    {
                        text: 'Ok',
                        class: 'confirm hide',
                        click: self.confirmForm
                    },
                    {
                        text: 'Send new Email',
                        class: 'refresh hide',
                        click: self.refreshForm
                    }]
            })
        },

        bindUIActions: function() {
            var self = this;
            this.button.on('click', function(e) {
                e.preventDefault();
                self.modalOpen();
            });
        },

        modalOpen: function() {
            this.popup.modal("openModal");
        },

        refreshForm: function() {
            var form = this.modal.find('#modal-overlay-form');
            var messageBox = this.modal.find('#message-box');
            var sendButton = this.modal.find('.send');
            var confirmButton = this.modal.find('.confirm');
            var refreshButton = this.modal.find('.refresh');

            form.find('input:not(#id)').val('');
            form.find('textarea').val('');

            messageBox.text('');
            form.toggleClass('hide');
            sendButton.toggleClass('hide');
            confirmButton.toggleClass('hide');
            refreshButton.toggleClass('hide');
        },

        confirmForm: function() {
            this.closeModal();
        },

        sendForm: function(e) {
            var form = this.modal.find('#modal-overlay-form');
            var messageBox = this.modal.find('#message-box');
            var sendButton = this.modal.find('.send');
            var confirmButton = this.modal.find('.confirm');
            var refreshButton = this.modal.find('.refresh');

            var scope = {
                form: form,
                messageBox: messageBox,
                sendButton: sendButton,
                confirmButton: confirmButton,
                refreshButton: refreshButton
            };

            var ajaxurl = form.attr('action');
            var data = form.serialize();

            console.log('sending...');
            e.preventDefault();
            if (!form.valid()) {
                console.log('form is not valid');
                return false;
            }
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                showLoader: true,
                dataType: 'json',
                data: data,
                self: this,
                scope: scope,
                error: function (xhr, status, errorThrown) {
                    console.log('Error in Ajax load');
                },
                success: function (msgData) {

                    scope.form.toggleClass('hide');
                    scope.messageBox.text('' + msgData['message'] + '');

                    scope.sendButton.toggleClass('hide');
                    scope.confirmButton.toggleClass('hide');
                    scope.refreshButton.toggleClass('hide');

                    console.log('' + msgData['message'] + '');
                }
            });
        }
    };

    sendMail.init();
});