from .exceptions import EmailException
from .interfaces import IContent, IEmailUser, ISending, ILocalInfo
from .local_info import ServicesOrdering
from .result import Result


class Sending(ISending):
    """
     * Send mails by correct service
     * Select the only one - first successful one
    """

    CALL_UNKNOWN = 590
    CALL_RUN_DIED = 591
    CALL_EXCEPTION = 592

    def __init__(self, info: ILocalInfo, ordering: ServicesOrdering):
        self._info = info
        self._services_iterator = ordering

    def can_use_service(self) -> bool:
        return self._services_iterator.can_use_service()

    def system_service_id(self) -> int:
        return self.SERVICE_SYSTEM

    def send_email(self, content: IContent, target: IEmailUser, source: IEmailUser = None, reply_to: IEmailUser = None, to_disabled: bool = False) -> Result:
        self._info.before_process(content, target, source)

        if self.can_use_service():
            for lib in self._services_iterator:
                if not Sending._is_allowed(lib):
                    continue

                result = None
                self._info.before_send(lib, content)
                try:
                    result = lib.send_email(content, target, source, reply_to, to_disabled)
                    if result.get_status():
                        self._info.when_result_is_successful(lib, result)
                        return result
                    else :
                        self._info.when_result_is_not_successful(lib, result)
                        self._services_iterator.remove_service(lib)  # throw it out, if we send more mail, then it won't be bothered anymore on this run
                        if self._services_iterator.is_returning_after_first_unsuccessful():
                            return result

                except EmailException as ex:
                    self._info.when_send_fails(lib, ex)

        self._info.when_no_definition_is_usable()
        return Result(False, self._info.get_lang_sending_failed())

    @staticmethod
    def _is_allowed(lib) -> bool:
        return isinstance(lib, ISending) and lib.can_use_service()
