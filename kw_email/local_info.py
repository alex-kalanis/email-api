
from .interfaces import ILocalInfo, ILocalProcessing, IContent, IEmailUser, ISending
from .exceptions import EmailException
from .result import Result


class DefaultInfo(ILocalInfo):
    """
     * Default information with specifics of local machine
     * By using class implementing same interface you can log everything during sending the content
    """

    def before_process(self, content: IContent, target: IEmailUser, source: IEmailUser = None):
        pass

    def before_send(self, service: ISending, content: IContent):
        pass

    def when_send_fails(self, service: ISending, ex: EmailException):
        pass

    def when_result_is_successful(self, service: ISending, result: Result):
        pass

    def when_result_is_not_successful(self, service: ISending, result: Result):
        pass

    def when_no_definition_is_usable(self):
        pass

    def get_lang_sending_failed(self) -> str:
        return 'Sending failed.'


class LocalProcessing(ILocalProcessing):

    def enable_mail_locally(self, target: IEmailUser):
        pass


class ServicesOrdering:
    """
     * Default services available on local installation
     * Contains typical pythonic object-to-array boilerplate
     * You can extend this class to specify order of available services
    """

    def __init__(self):
        self._services = {}
        self._keys = []
        self._processed = []
        self._return_on_unsuccessful = False

    def __iter__(self):
        self._keys = self._services.keys()
        self._processed = []
        return self

    def __next__(self):
        # PLEASE, can someone write that better?
        # want: pass dict per item as result of iteration
        available = [key for key in self._keys if key not in self._processed]
        for key in available:
            self._processed.append(key)
            return self._services[key]
        raise StopIteration()

    def add_service(self, service: ISending):
        self._services[service.system_service_id()] = service
        return self

    def remove_service(self, service: ISending):
        del self._services[service.system_service_id()]
        return self

    def may_return_first_unsuccessful(self, sets: bool = False):
        self._return_on_unsuccessful = sets
        return self

    def is_returning_after_first_unsuccessful(self) -> bool:
        return self._return_on_unsuccessful

    def can_use_service(self) -> bool:
        return len(self._services) > 0
