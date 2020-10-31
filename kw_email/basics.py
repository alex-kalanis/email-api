
from .interfaces import IContent, IContentAttachment, IEmailUser


class Content(IContent):
    """
     * Smallest possible email content
    """

    def __init__(self):
        self.subject = ''
        self.body = ''
        self.tag = ''
        self.plain = None
        self.unsub_email = None
        self.unsub_link = None
        self.unsub_by_click = False
        self._attachments = []

    def set_data(self, subject: str = '', body: str = '', tag: str = ''):
        self.subject = subject
        self.body = body
        self.tag = tag
        return self

    def sanitize(self):
        self.subject = str(self.subject) if self.subject is not None else ''
        self.body = str(self.body) if self.body is not None else ''
        self.tag = str(self.tag) if self.tag is not None else ''
        self.plain = str(self.plain) if self.plain is not None else None
        self.unsub_email = str(self.unsub_email) if self.unsub_email is not None else None
        self.unsub_link = str(self.unsub_link) if self.unsub_link is not None else None
        self.unsub_by_click = bool(self.unsub_by_click) if self.subject is not None else False
        return self

    def get_subject(self) -> str:
        return str(self.subject)

    def get_html_body(self) -> str:
        return str(self.body)

    def get_plain_body(self) -> str:
        return self.plain

    def get_tag(self) -> str:
        return str(self.tag)

    def get_unsubscribe_email(self) -> str:
        return self.unsub_email

    def get_unsubscribe_link(self) -> str:
        return self.unsub_link

    def can_unsubscribe_one_click(self) -> bool:
        return bool(self.unsub_by_click)

    def add_attachment(self, attachment: IContentAttachment):
        self._attachments.append(attachment)
        return self

    def get_attachments(self):
        return self._attachments

    def reset_attachments(self):
        self._attachments = []
        return self


class Attachment(IContentAttachment):

    def __init__(self):
        self.name = ''
        self.path = ''
        self.content = ''
        self.mime = ''
        self.encoding = ''
        self.type = self.TYPE_INLINE

    def set_data(self, name: str, path: str = '', content: str = '', mime: str = '', encoding: str = '', type: int = IContentAttachment.TYPE_INLINE):
        self.name = name
        self.path = path
        self.mime = mime
        self.content = content
        self.encoding = encoding
        self.type = type
        return self

    def sanitize(self):
        self.name = str(self.name) if self.name is not None else ''
        self.path = str(self.path) if self.path is not None else ''
        self.mime = str(self.mime) if self.mime is not None else ''
        self.content = str(self.content) if self.content is not None else ''
        self.encoding = str(self.encoding) if self.encoding is not None else ''
        self.type = int(self.type) if self.type is not None else ''
        return self

    def get_file_name(self) -> str:
        return str(self.name)

    def get_file_content(self) -> str:
        return str(self.content)

    def get_local_path(self) -> str:
        return str(self.path)

    def get_file_mime(self) -> str:
        return str(self.mime)

    def get_encoding(self) -> str:
        return str(self.encoding)

    def get_type(self) -> int:
        return int(self.type)


class User(IEmailUser):
    """
     * Simple implementation of user which sends the emails
    """

    def __init__(self):
        self.email = ''
        self.name = ''

    def set_data(self, email: str, name: str = ''):
        self.name = name
        self.email = email
        return self

    def sanitize(self):
        self.name = str(self.name) if self.name is not None else ''
        self.email = str(self.email) if self.email is not None else ''
        return self

    def get_email(self) -> str:
        return str(self.email)

    def get_email_name(self) -> str:
        return str(self.name)
