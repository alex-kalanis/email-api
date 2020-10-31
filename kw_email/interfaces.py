from .exceptions import EmailException
from .result import Result


class IContent:
    """
     * Interface which describes email content which will be sent.
     * Concrete implementation in on implementing class.
    """
    def get_subject(self) -> str:
        """
         * Mail subject
        """
        raise NotImplementedError('TBI')

    def get_html_body(self) -> str:
        """
         * Mail content - usual HTML body
        """
        raise NotImplementedError('TBI')

    def get_plain_body(self) -> str:
        """
         * Mail content - plaintext
        """
        raise NotImplementedError('TBI')

    def get_tag(self) -> str:
        """
         * Mail tags for sorting on external services
        """
        raise NotImplementedError('TBI')

    def get_unsubscribe_email(self) -> str:
        """
         * Email for unsubscribe
        """
        raise NotImplementedError('TBI')

    def get_unsubscribe_link(self) -> str:
        """
         * Link for unsubscribe
        """
        raise NotImplementedError('TBI')

    def can_unsubscribe_one_click(self) -> bool:
        """
         * Can usubscribe with one click?
        """
        raise NotImplementedError('TBI')

    def get_attachments(self):
        """
         * Attachments
        """
        raise NotImplementedError('TBI')


class IContentAttachment:
    """
     * Interface for adding attachments into mail
     * Specific implementation in on implementing class.
    """

    TYPE_INLINE = 1
    TYPE_FILE = 2
    TYPE_IMAGE = 3

    def get_file_name(self) -> str:
        """
         * Name of attached file
         * Can be empty string
        """
        raise NotImplementedError('TBI')

    def get_file_content(self) -> str:
        """
         * Attachment content
         * Can be empty when passed as file from local drive
         * For TYPE_IMAGE it contains ContentID - where to add this image into the message
        """
        raise NotImplementedError('TBI')

    def get_local_path(self) -> str:
        """
         * Path to file on local system
         * Can be empty when sent as inline record
        """
        raise NotImplementedError('TBI')

    def get_file_mime(self) -> str:
        """
         * File Mime Type
         * Can be empty, then it's on sending library
        """
        raise NotImplementedError('TBI')

    def get_encoding(self) -> str:
        """
         * Mailer encodes results in...
         * usually base64
        """
        raise NotImplementedError('TBI')

    def get_type(self) -> int:
        """
         * Content type - depends on constants above
        """
        raise NotImplementedError('TBI')


class IEmailUser:
    """
     * Interface for targeting mail
    """

    def get_email(self) -> str:
        """
         * Returns email of user
        """
        raise NotImplementedError('TBI')

    def get_email_name(self) -> str:
        """
         * Returns name of user
        """
        raise NotImplementedError('TBI')


class ILocalProcessing:
    """
     * What to do with mail when it's something need locally
    """

    def enable_mail_locally(self, target: IEmailUser):
        """
         * Remove blocks made on local machine by callbacks
         * @param IEmailUser target Who will be enabled locally
        """
        raise NotImplementedError('TBI')


class ISending:
    """
     * Main interface for sending an email
     * Implementing class process the sending itself
     * Calling class has a choice which service will be used
    """

    SERVICE_SYSTEM = 0
    SERVICE_TESTING = 1

    def can_use_service(self) -> bool:
        """
         * Can use for sending this E-mail?
         * Is there correct all dependencies?
        """

    def system_service_id(self) -> int:
        """
         * Which ID is for this service?
         * @return int
         *
         * 0 -> system calls
         * 1 -> for testing
         * 2 -> mail() inside the language
        """
        raise NotImplementedError('TBI')

    def send_email(self, content: IContent, target: IEmailUser, source: IEmailUser = None, reply_to: IEmailUser = None, to_disabled: bool = False) -> Result:
        """
         * @param Content $content Message with attachments
         * @param EmailUser $to Target user
         * @param EmailUser|null $from Who sends the message
         * @param EmailUser|null $replyTo reply to this user - for larger services
         * @param bool $toDisabled When user bounced mail then it's necessary to pass info for skip check
         * @return Result
         * @throws EmailException
        """
        raise NotImplementedError('TBI')


class ILocalInfo:
    """
     * Interface for setting info about local environment
     * Implementing class process local-system-dependent calls
    """

    def before_process(self, content: IContent, target: IEmailUser, source: IEmailUser = None):
        """
         * For log whole action of sending a mail
        """
        raise NotImplementedError('TBI')

    def before_send(self, service: ISending, content: IContent):
        """
         * For log which service did it
        """
        raise NotImplementedError('TBI')

    def when_send_fails(self, service: ISending, ex: EmailException):
        """
         * We have got an exception from sending service - something weird happend
         * Position to log it
        """
        raise NotImplementedError('TBI')

    def when_result_is_successful(self, service: ISending, result: Result):
        """
         * Log it when there is successful result from service
        """
        raise NotImplementedError('TBI')

    def when_result_is_not_successful(self, service: ISending, result: Result):
        """
         * When sending returns fail for any reason
        """
        raise NotImplementedError('TBI')

    def when_no_definition_is_usable(self):
        """
         * When there is nothing to do because there is no available definition
         * Log somewhere that we have unknown sending services
        """
        raise NotImplementedError('TBI')

    def get_lang_sending_failed(self) -> str:
        """
         * Translation for totally dead result
        """
        raise NotImplementedError('TBI')
