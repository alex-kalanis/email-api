import unittest

from kw_email.basics import Content, Attachment, User
from kw_email.result import Result


class CommonTestClass(unittest.TestCase):

    def _mock_content(self) -> Content:
        return Content().set_data(
            'testing content',
            'qwertzuiopasdfghjklyxcvbnm',
            'on_testing_service'
        )

    def _mock_attachment(self) -> Attachment:
        return Attachment().set_data(
            'testing_file',
            '',
            'qwertzuiopasdfghjklyxcvbnm',
            'text/plain',
            'utf8'
        )

    def _mock_user(self) -> User:
        return User().set_data(
            'bob@test.example',
            'Bob'
        )

    def _mock_result(self, status: bool) -> Result:
        return Result(status, 'Testing response')
