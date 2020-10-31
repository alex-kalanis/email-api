
class Result:
    """
     * Result from sending service
    """

    def __init__(self, status: bool = False, data: str = None, remote_id: str = None):
        self.status = status
        self.data = data
        self.remote_id = remote_id

    def sanitize(self):
        self.status = bool(self.status) if self.status is not None else False
        self.data = str(self.data) if self.data is not None else None
        self.remote_id = str(self.remote_id) if self.remote_id is not None else None

    def get_status(self) -> bool:
        return bool(self.status)

    def get_data(self) -> str:
        return self.data

    def get_remote_id(self) -> str:
        return self.remote_id
