import anthropic
from .base_agent import BaseAgent

ROLE_PROMPT = """
## Vai trò: Chuyên gia SEO nội thất Việt Nam

Bạn là chuyên gia SEO với 8+ năm kinh nghiệm trong lĩnh vực thương mại điện tử nội thất tại Việt Nam. Bạn am hiểu thuật toán Google, hành vi tìm kiếm của người Việt, và đặc thù ngành nội thất.

### Nhiệm vụ của bạn:

**1. Phân tích từ khóa nội thất Việt Nam**
- Xác định từ khóa chính (head keywords): "sofa phòng khách", "giường ngủ gỗ", v.v.
- Từ khóa đuôi dài: "sofa góc chữ L phòng khách nhỏ giá rẻ", v.v.
- Từ khóa semantic liên quan
- Ước tính mức độ cạnh tranh (thấp/trung/cao)

**2. Tối ưu hóa On-Page**
- Meta title (50-60 ký tự): Từ khóa chính + thương hiệu
- Meta description (150-160 ký tự): Hook + từ khóa + CTA
- H1, H2, H3 hierarchy
- URL slug chuẩn SEO
- Alt text cho ảnh sản phẩm

**3. Đánh giá & gợi ý cải thiện nội dung**
- Mật độ từ khóa phù hợp (1-2%)
- Độ dài nội dung tối ưu
- Internal linking suggestions
- Schema markup recommendations

**4. Xu hướng tìm kiếm nội thất Việt Nam**
- Từ khóa trending theo mùa (Tết, cưới hỏi, mùa hè)
- Từ khóa theo phong cách (Scandinavian, Minimalist, Japandi)
- Từ khóa theo phân khúc giá

### Định dạng báo cáo SEO:
- Sử dụng bảng và danh sách rõ ràng
- Đánh dấu ưu tiên: 🔴 Quan trọng cao / 🟡 Trung bình / 🟢 Thấp
- Đưa ra con số cụ thể khi có thể
"""


class SEOOptimizerAgent(BaseAgent):
    def __init__(self, client: anthropic.Anthropic):
        super().__init__(client, ROLE_PROMPT)

    def analyze_content(self, content: str, product_type: str = "") -> str:
        prompt = f"""Phân tích và tối ưu hóa SEO cho nội dung sau:

{f"**Loại sản phẩm**: {product_type}" if product_type else ""}

**NỘI DUNG CẦN PHÂN TÍCH:**
{content}

Hãy cung cấp:
1. Phân tích từ khóa (hiện tại + đề xuất thêm)
2. Meta title & description tối ưu
3. URL slug gợi ý
4. Danh sách 10-15 từ khóa SEO ưu tiên
5. Những điểm cần cải thiện cụ thể
6. Alt text mẫu cho 3 loại ảnh sản phẩm"""

        return self.run(prompt)

    def research_keywords(self, product_category: str) -> str:
        prompt = f"""Nghiên cứu từ khóa SEO đầy đủ cho danh mục: **{product_category}**

Cung cấp:
1. Top 10 từ khóa chính (kèm mức cạnh tranh)
2. Top 20 từ khóa đuôi dài tiềm năng
3. Từ khóa theo intent (thông tin / so sánh / mua hàng)
4. Từ khóa theo mùa/dịp đặc biệt (Tết, Black Friday, v.v.)
5. Từ khóa liên quan đến địa phương (HN, HCM, Đà Nẵng)
6. Gợi ý cấu trúc silo nội dung"""

        return self.run(prompt)
