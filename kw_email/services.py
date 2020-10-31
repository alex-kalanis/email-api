
from kw_email.interfaces import ISending, IContent, IEmailUser
from kw_email.result import Result
import smtplib


class Internal(ISending):
    """
     * Make and send each mail
    """

    def __init__(self):
        try:
            self._server = smtplib.SMTP('localhost')
        except ConnectionRefusedError:
            self._server = None

    def __del__(self):
        if (self._server):
            self._server.quit()

    def can_use_service(self) -> bool:
        return True

    def system_service_id(self) -> int:
        return 1

    def send_email(self, content: IContent, target: IEmailUser, source: IEmailUser = None, reply_to: IEmailUser = None, to_disabled: bool = False) -> Result:
        """
         * Send mail directly via python - no hurdles anywhere, no security too
        """
        if len(content.get_attachments()) > 0:
            return Result(False, 'No attachments available for simple mailing')

        from_addr = source.get_email() if source else ''
        if self._server:
            errs = self._server.sendmail(from_addr, target.get_email(), content.get_html_body())
        else:
            errs = {'Not connected'}

        return Result(len(errs) < 1, '')
