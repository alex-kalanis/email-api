from kw_tests.common_class import CommonTestClass
from kw_email.exceptions import EmailException
from kw_email.local_info import LocalProcessing, DefaultInfo, ServicesOrdering
from kw_email.interfaces import IContent, IEmailUser, ISending
from kw_email.result import Result
from kw_email.sending import Sending
from kw_email.services import Internal


class RestTest(CommonTestClass):

    def test_internal(self):
        lib = Internal()
        assert lib.can_use_service()
        assert 1 == lib.system_service_id()
        # more is not possible - here is direct system call for email

    def test_internal_dies(self):
        data = self._mock_content()
        data.add_attachment(self._mock_attachment())
        lib = Internal()
        result = lib.send_email(data, self._mock_user())
        assert not result.get_status()

    def test_local_processing(self):
        lib = LocalProcessing()  # necessary in subservices
        lib.enable_mail_locally(self._mock_user())
        assert True  # because coverage sniffing


class DummyService(ISending):

    def __init__(self, can_use_service: bool = True, get_passed_result: bool = True, get_failed_result: bool = True):
        self._can_use_service = can_use_service
        self._get_passed_result = get_passed_result
        self._get_failed_result = get_failed_result

    def can_use_service(self) -> bool:
        return bool(self._can_use_service)

    def system_service_id(self) -> int:
        return self.SERVICE_TESTING

    def send_email(self, content: IContent, target: IEmailUser, source: IEmailUser = None, reply_to: IEmailUser = None, to_disabled: bool = False) -> Result:
        if self._get_passed_result:
            return Result(
                True if content.get_html_body() and target.get_email() else False,
                'Dummy service with check'
            )
        if self._get_failed_result:
            return Result(False, 'died')
        raise EmailException('die on send')


class SendingStopBeforeProcess(DefaultInfo):

    def before_process(self, content: IContent, target: IEmailUser, source: IEmailUser = None):
        super().before_process(content, target, source)
        raise EmailException('Catch on before process')


class SendingStopBeforeSend(DefaultInfo):

    def before_send(self, service: ISending, content: IContent):
        super().before_send(service, content)
        raise EmailException('Catch on before send')


class SendingStopResultSuccess(DefaultInfo):

    def when_result_is_successful(self, service: ISending, result: Result):
        super().when_result_is_successful(service, result)
        raise EmailException('Catch on success send')

    def when_send_fails(self, service: ISending, ex: EmailException):
        super().when_send_fails(service, ex)
        raise ex  # pass it through catch


class SendingTest(CommonTestClass):

    def test_check(self):
        lib = Sending(DefaultInfo(), self._mock_services(False))
        assert not lib.can_use_service(), 'There is no service by default'
        assert 0 == lib.system_service_id()
        lib = Sending(DefaultInfo(), self._mock_services())
        assert lib.can_use_service(), 'There is services'

    def test_simple(self):
        lib = Sending(DefaultInfo(), self._mock_services())
        data = lib.send_email(self._mock_content(), self._mock_user())
        assert data.get_status()
        assert 'Dummy service with check' == data.get_data()
        assert data.get_remote_id() is None

    def test_process_before(self):
        try:
            lib = Sending(SendingStopBeforeProcess(), self._mock_services())
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'Catch on before process' == ex.get_message()

    def test_before_send(self):
        try:
            lib = Sending(SendingStopBeforeSend(), self._mock_services())
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'Catch on before send' == ex.get_message()

    def test_process_success(self):
        try:
            lib = Sending(SendingStopResultSuccess(), self._mock_services())
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'Catch on success send' == ex.get_message()

    def _mock_services(self, with_dummy_service: bool = True) -> ServicesOrdering:
        ordering = ServicesOrdering()
        if with_dummy_service:
            ordering.add_service(DummyService())
        return ordering


class HaltedNothingLeft(DefaultInfo):

    def when_no_definition_is_usable(self):
        super().when_no_definition_is_usable()
        raise EmailException('No service left')


class HaltedSendFail(DefaultInfo):

    def when_send_fails(self, service: ISending, ex: EmailException):
        super().when_send_fails(service, ex)
        raise EmailException('Catch on failed service', None, ex)


class HaltedResultFail(DefaultInfo):

    def when_result_is_not_successful(self, service: ISending, result: Result):
        super().when_result_is_not_successful(service, result)
        raise EmailException('Catch on failed result')

    def when_send_fails(self, service: ISending, ex: EmailException):
        super().when_send_fails(service, ex)
        raise ex  # pass it through catch


class SendingFailTest(CommonTestClass):

    def test_no_service_set(self):
        lib = Sending(DefaultInfo(), self._mock_services(False))
        data = lib.send_email(self._mock_content(), self._mock_user())
        assert not data.get_status()
        assert 'Sending failed.' == data.get_data()

    def test_no_service_except(self):
        try:
            lib = Sending(HaltedNothingLeft(), self._mock_services(False))
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'No service left' == ex.get_message()

    def test_no_service_left(self):
        lib = Sending(DefaultInfo(), self._mock_services(True, True, False))
        data = lib.send_email(self._mock_content(), self._mock_user())
        assert not data.get_status()
        assert 'Sending failed.' == data.get_data()

    def test_sending_died(self):
        lib = Sending(HaltedSendFail(), self._mock_services().may_return_first_unsuccessful(True))
        data = lib.send_email(self._mock_content(), self._mock_user())
        assert not data.get_status()
        assert 'died' == data.get_data()

    def test_sending_died_result(self):
        try:
            lib = Sending(HaltedResultFail(), self._mock_services())
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'Catch on failed result' == ex.get_message()

    def test_sending_died_except(self):
        try:
            lib = Sending(HaltedResultFail(), self._mock_services(True, False))
            lib.send_email(self._mock_content(), self._mock_user())
            assert False, 'not stop'
        except EmailException as ex:
            assert 'die on send' == ex.get_message()

    def _mock_services(self, with_dummy_service: bool = True, get_result: bool = True, can_use_service: bool = True) -> ServicesOrdering:
        ordering = ServicesOrdering()
        if with_dummy_service:
            service = DummyService(can_use_service, False, get_result)
            ordering.add_service(service)
        return ordering
