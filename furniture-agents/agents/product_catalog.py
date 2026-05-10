import anthropic
from .base_agent import BaseAgent

ROLE_PROMPT = """
## Vai trò: Chuyên gia Quản lý Danh mục Sản phẩm & Schema Markup

Bạn là chuyên gia về WooCommerce và structured data, giúp tổ chức danh mục sản phẩm nội thất và tạo JSON-LD schema markup chuẩn Google.

### Nhiệm vụ chính:

**1. Cấu trúc danh mục sản phẩm WooCommerce**
- Phân cấp danh mục (Category hierarchy)
- Thuộc tính sản phẩm (Product attributes): chất liệu, màu sắc, kích thước, phong cách
- Tags sản phẩm thông minh
- SKU naming convention chuẩn
- Product variations (biến thể: màu, size)

**2. JSON-LD Schema Markup**
Tạo schema markup đầy đủ theo Google Search guidelines:
- Product schema: name, description, image, offers, brand, review
- BreadcrumbList schema
- Organization schema
- FAQPage schema (cho trang sản phẩm)

**3. Phân loại & gắn thẻ sản phẩm**
- Phân loại theo phòng: phòng khách, phòng ngủ, phòng ăn, phòng làm việc
- Phân loại theo vật liệu
- Phân loại theo phong cách
- Phân loại theo giá: dưới 5tr, 5-10tr, 10-20tr, trên 20tr

**4. WooCommerce Product Data**
Format dữ liệu sản phẩm chuẩn WooCommerce:
- Giá gốc & giá sale
- Tồn kho (stock management)
- Trọng lượng & kích thước (cho tính phí ship)
- Sản phẩm liên quan (related products)
- Upsell & cross-sell

### Định dạng output:
- JSON-LD phải valid, có thể paste trực tiếp vào WordPress
- Cấu trúc danh mục dạng outline rõ ràng
- Bảng thuộc tính sản phẩm đầy đủ
"""


class ProductCatalogAgent(BaseAgent):
    def __init__(self, client: anthropic.Anthropic):
        super().__init__(client, ROLE_PROMPT)

    def generate_schema(
        self,
        product_name: str,
        description: str,
        price: str,
        category: str,
        brand: str = "Nội thất Việt",
        sku: str = "",
        image_url: str = "https://example.com/image.jpg",
    ) -> str:
        prompt = f"""Tạo JSON-LD Schema Markup đầy đủ cho sản phẩm sau:

**Tên sản phẩm**: {product_name}
**Danh mục**: {category}
**Mô tả**: {description}
**Giá**: {price} VNĐ
**Thương hiệu**: {brand}
{f"**SKU**: {sku}" if sku else ""}
**URL ảnh**: {image_url}

Tạo:
1. Product JSON-LD Schema (đầy đủ các trường: name, description, image, offers, brand, sku, material, color)
2. BreadcrumbList Schema cho đường dẫn: Trang chủ > {category} > {product_name}
3. FAQPage Schema với 5 câu hỏi thường gặp về sản phẩm này
4. Gợi ý cấu trúc danh mục WooCommerce phù hợp
5. Danh sách attributes & tags cho WooCommerce"""

        return self.run(prompt)

    def suggest_catalog_structure(self, product_list: str) -> str:
        prompt = f"""Dựa trên danh sách sản phẩm sau, hãy đề xuất cấu trúc danh mục WooCommerce tối ưu:

{product_list}

Cung cấp:
1. Cấu trúc danh mục phân cấp (tối đa 3 cấp)
2. Thuộc tính sản phẩm (Attributes) cần tạo trong WooCommerce
3. SKU naming convention
4. Gợi ý tag system
5. Internal linking structure giữa các danh mục"""

        return self.run(prompt)
