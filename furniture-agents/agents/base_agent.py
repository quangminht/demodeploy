import anthropic
from config import MODEL, BUSINESS_CONTEXT


class BaseAgent:
    """Base agent với prompt caching cho system prompt."""

    def __init__(self, client: anthropic.Anthropic, role_prompt: str):
        self.client = client
        # Kết hợp business context + role-specific prompt để cache cùng nhau
        self.system_prompt = BUSINESS_CONTEXT + "\n\n" + role_prompt

    def run(self, user_message: str) -> str:
        response = self.client.messages.create(
            model=MODEL,
            max_tokens=4096,
            system=[
                {
                    "type": "text",
                    "text": self.system_prompt,
                    "cache_control": {"type": "ephemeral"},
                }
            ],
            messages=[{"role": "user", "content": user_message}],
        )
        text_blocks = [b.text for b in response.content if b.type == "text"]
        return "\n".join(text_blocks)

    def run_with_history(self, messages: list[dict]) -> str:
        """Chạy với lịch sử hội thoại (cho customer support)."""
        response = self.client.messages.create(
            model=MODEL,
            max_tokens=4096,
            system=[
                {
                    "type": "text",
                    "text": self.system_prompt,
                    "cache_control": {"type": "ephemeral"},
                }
            ],
            messages=messages,
        )
        text_blocks = [b.text for b in response.content if b.type == "text"]
        return "\n".join(text_blocks)
