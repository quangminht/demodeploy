import anthropic
from .base_agent import BaseAgent

ROLE_PROMPT = """
## Vai trò: Chuyên gia Marketing Content Nội thất Việt Nam

Bạn là chuyên gia marketing digital với kinh nghiệm sâu về ngành nội thất, am hiểu tâm lý người tiêu dùng Việt và các nền tảng mạng xã hội phổ biến tại Việt Nam (Facebook, Zalo, Instagram, TikTok).

### Kỹ năng & chuyên môn:

**Facebook Marketing (nền tảng chính):**
- Post bán hàng: storytelling kết hợp sản phẩm
- Caption ảnh: ngắn gọn, có hook, emoji phù hợp
- Quảng cáo Facebook Ads: headline, primary text, description
- Livestream script: kịch bản giới thiệu sản phẩm
- Comment template: phản hồi khách hàng comment

**Zalo Marketing:**
- Zalo OA broadcast message: cập nhật sản phẩm mới, khuyến mãi
- Zalo chat: tư vấn bán hàng qua tin nhắn
- Zalo Story content

**Instagram:**
- Caption ngắn + hashtag (#nộithất #nộithấtviệt #scandinavian...)
- Stories content (15 giây, swipe up)
- Reels concept (ý tưởng video)

**Email Marketing:**
- Subject line hấp dẫn (40-50 ký tự)
- Email body cấu trúc rõ ràng
- CTA button text

**Banner & Promotional:**
- Headline cho banner quảng cáo (tối đa 10 từ)
- Flash sale copy
- Seasonal campaign: Tết, 8/3, 20/10, Black Friday

### Nguyên tắc viết copy Việt Nam:
- Dùng từ ngữ gần gũi, tránh từ Hán-Việt khó hiểu
- Emoji phù hợp ngữ cảnh, không lạm dụng
- Số liệu cụ thể tạo niềm tin (giảm 30%, bảo hành 24 tháng)
- FOMO elements: "còn 3 suất", "ưu đãi hết 31/12"
- Tone: ấm áp, chân thực, không bán hàng quá lộ liễu
- Hashtag: 5-10 hashtag tiếng Việt + 3-5 tiếng Anh

### Cấu trúc post bán hàng hiệu quả:
1. Hook (câu đầu tiên giật tít)
2. Vấn đề/nhu cầu của khách hàng
3. Giải pháp (sản phẩm)
4. Social proof hoặc USP
5. CTA rõ ràng
"""


class MarketingCopyAgent(BaseAgent):
    def __init__(self, client: anthropic.Anthropic):
        super().__init__(client, ROLE_PROMPT)

    def create_social_post(
        self,
        product_name: str,
        platform: str,
        campaign_goal: str,
        key_message: str = "",
        promotion: str = "",
    ) -> str:
        prompt = f"""Tạo nội dung marketing cho nền tảng **{platform}**:

**Sản phẩm**: {product_name}
**Mục tiêu chiến dịch**: {campaign_goal}
{f"**Thông điệp chính**: {key_message}" if key_message else ""}
{f"**Khuyến mãi**: {promotion}" if promotion else ""}

Tạo đầy đủ:
1. **Caption/Post chính** (phù hợp độ dài {platform})
2. **Hashtag** (tiếng Việt + tiếng Anh)
3. **Biến thể A/B** (1 phiên bản khác để test)
4. **Gợi ý ảnh/video** đi kèm
5. **Thời điểm đăng** tối ưu và **tần suất** đề xuất"""

        return self.run(prompt)

    def create_email_campaign(
        self,
        campaign_name: str,
        target_audience: str,
        promotion_detail: str,
    ) -> str:
        prompt = f"""Tạo email marketing campaign:

**Tên chiến dịch**: {campaign_name}
**Đối tượng**: {target_audience}
**Chi tiết ưu đãi**: {promotion_detail}

Tạo:
1. **Subject line** (3 phiên bản A/B/C, 40-50 ký tự)
2. **Preview text** (90 ký tự)
3. **Email body** đầy đủ (header, nội dung, CTA, footer)
4. **CTA button text** (3 lựa chọn)
5. **Gợi ý thời gian gửi** và **follow-up sequence** (3 email)"""

        return self.run(prompt)

    def create_banner_copy(self, product: str, promotion: str, deadline: str = "") -> str:
        prompt = f"""Tạo copy cho banner quảng cáo:

**Sản phẩm**: {product}
**Khuyến mãi**: {promotion}
{f"**Deadline**: {deadline}" if deadline else ""}

Tạo:
1. **Headline chính** (tối đa 8 từ, impactful)
2. **Sub-headline** (tối đa 15 từ)
3. **CTA button** (3-5 từ)
4. **Badge/Label text** (ví dụ: "HOT DEAL", "MỚI VỀ")
5. **5 biến thể headline** khác nhau để lựa chọn"""

        return self.run(prompt)
