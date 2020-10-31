from kw_tests.common_class import CommonTestClass
from kw_email.basics import Attachment, Content
from kw_email.result import Result


class AttachmentTest(CommonTestClass):

    def test_simple(self):
        data = self._mock_attachment()
        assert 'testing_file' == data.name
        assert '' == data.path
        assert 'text/plain' == data.mime
        assert 'qwertzuiopasdfghjklyxcvbnm' == data.content
        assert 'utf8' == data.encoding
        assert Attachment.TYPE_INLINE == data.type

    def test_outputs(self):
        data = self._mock_attachment()
        assert 'testing_file' == data.get_file_name()
        assert '' == data.get_local_path()
        assert 'text/plain' == data.get_file_mime()
        assert 'qwertzuiopasdfghjklyxcvbnm' == data.get_file_content()
        assert 'utf8' == data.get_encoding()
        assert Attachment.TYPE_INLINE == data.get_type()

    def test_sanitize(self):
        data = self._mock_attachment()
        data.type = '3'
        data.path = None
        data.sanitize()
        assert '' == data.path
        assert Attachment.TYPE_IMAGE == data.type


class ContentTest(CommonTestClass):

    def test_simple(self):
        data = self._mock_content()
        assert 'testing content', data.subject
        assert 'qwertzuiopasdfghjklyxcvbnm', data.body
        assert 'on_testing_service', data.tag

    def test_outputs(self):
        data = self._mock_content()
        assert 'testing content', data.get_subject()
        assert 'qwertzuiopasdfghjklyxcvbnm', data.get_html_body()
        assert 'on_testing_service', data.get_tag()

    def test_clear(self):
        data = Content()
        data.set_data('testing content 2', 'qwertzuiopasdfghjklyxcvbnm123', 'on_testing_service456')
        data.plain = 'qwertzuiopasdfghjklyxcvbnm987'
        data.unsub_email = 'qwertzuiopasdfghjklyxcvbnm654'
        data.unsub_link = 'qwertzuiopasdfghjklyxcvbnm321'
        data.unsub_by_click = 0
        data.sanitize()
        assert 'testing content 2' == data.get_subject()
        assert 'qwertzuiopasdfghjklyxcvbnm123' == data.get_html_body()
        assert 'on_testing_service456' == data.get_tag()
        assert 'qwertzuiopasdfghjklyxcvbnm987' == data.get_plain_body()
        assert 'qwertzuiopasdfghjklyxcvbnm654' == data.get_unsubscribe_email()
        assert 'qwertzuiopasdfghjklyxcvbnm321' == data.get_unsubscribe_link()
        assert not data.can_unsubscribe_one_click()

    def test_attachments(self):
        data = self._mock_content()
        assert 0 == len(data.get_attachments())
        data.add_attachment(self._mock_attachment())
        assert 0 < len(data.get_attachments())
        data.reset_attachments()
        assert 0 == len(data.get_attachments())

    def test_sanitize(self):
        data = self._mock_content()
        data.subject = 123456789
        data.tag = None
        data.sanitize()
        assert '' == data.tag
        assert '123456789' == data.subject


class UserTest(CommonTestClass):

    def test_simple(self):
        data = self._mock_user()
        assert 'bob@test.example' == data.email
        assert 'Bob' == data.name

    def test_outputs(self):
        data = self._mock_user()
        assert 'bob@test.example' == data.get_email()
        assert 'Bob' == data.get_email_name()

    def test_sanitize(self):
        data = self._mock_user()
        data.name = None
        data.sanitize()
        assert '' == data.name


class ResultTest(CommonTestClass):

    def test_simple(self):
        data = self._mock_result(True)
        assert data.status
        assert 'Testing response' == data.data

    def test_clear(self):
        data = Result(True, 'none', '12')
        assert data.status
        assert 'none' == data.data
        assert '12' == data.remote_id
        assert '12' == data.get_remote_id()

    def test_outputs(self):
        data = self._mock_result(False)
        assert not data.get_status()
        assert 'Testing response' == data.get_data()
