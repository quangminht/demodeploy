#!/usr/bin/env python3
"""
Furniture Agents CLI - Hệ thống AI agents cho website nội thất WordPress
"""

import os
import sys
import anthropic
from rich.console import Console
from rich.panel import Panel
from rich.prompt import Prompt, Confirm
from rich.table import Table
from rich.markdown import Markdown
from rich import print as rprint

from agents import (
    ContentGeneratorAgent,
    SEOOptimizerAgent,
    CustomerSupportAgent,
    ProductCatalogAgent,
    MarketingCopyAgent,
)
from config import AGENT_DESCRIPTIONS

console = Console()


def get_client() -> anthropic.Anthropic:
    api_key = os.environ.get("ANTHROPIC_API_KEY")
    if not api_key:
        console.print("[red]❌ Lỗi: Chưa set ANTHROPIC_API_KEY[/red]")
        console.print("Chạy: [yellow]export ANTHROPIC_API_KEY='your-key'[/yellow]")
        sys.exit(1)
    return anthropic.Anthropic(api_key=api_key)


def show_welcome():
    console.print(Panel.fit(
        "[bold cyan]🪑 FURNITURE AGENTS - AI Assistant cho Website Nội Thất[/bold cyan]\n"
        "[dim]Powered by Claude Opus 4.7 với Prompt Caching[/dim]",
        border_style="cyan",
    ))


def show_menu():
    table = Table(title="Chọn Agent", border_style="blue", show_lines=True)
    table.add_column("Số", style="cyan", justify="center", width=4)
    table.add_column("Agent", style="bold")
    table.add_column("Chức năng", style="dim")

    menu_items = [
        ("1", "Content Generator", AGENT_DESCRIPTIONS["content"]),
        ("2", "SEO Optimizer", AGENT_DESCRIPTIONS["seo"]),
        ("3", "Customer Support", AGENT_DESCRIPTIONS["support"]),
        ("4", "Product Catalog", AGENT_DESCRIPTIONS["catalog"]),
        ("5", "Marketing Copy", AGENT_DESCRIPTIONS["marketing"]),
        ("0", "Thoát", "Exit"),
    ]

    for num, name, desc in menu_items:
        table.add_row(num, name, desc)

    console.print(table)


def run_content_generator(agent: ContentGeneratorAgent):
    console.print(Panel("[bold]✍️  Content Generator Agent[/bold]", border_style="green"))

    console.print("\n[bold]Nhập thông tin sản phẩm:[/bold]")
    product_name = Prompt.ask("  Tên sản phẩm")
    material = Prompt.ask("  Vật liệu (ví dụ: gỗ sồi tự nhiên, da PU)")
    dimensions = Prompt.ask("  Kích thước (ví dụ: 200x90x80 cm)")
    style = Prompt.ask("  Phong cách (Scandinavian/Modern/Japandi/...)")
    extra_info = Prompt.ask("  Thông tin thêm (Enter để bỏ qua)", default="")

    with console.status("[cyan]Đang tạo mô tả sản phẩm...[/cyan]"):
        result = agent.generate(product_name, material, dimensions, style, extra_info)

    console.print(Panel(Markdown(result), title="📝 Mô tả sản phẩm", border_style="green"))


def run_seo_optimizer(agent: SEOOptimizerAgent):
    console.print(Panel("[bold]🔍 SEO Optimizer Agent[/bold]", border_style="yellow"))

    console.print("\n[bold]Chọn tác vụ SEO:[/bold]")
    console.print("  [cyan]1[/cyan]. Phân tích & tối ưu nội dung có sẵn")
    console.print("  [cyan]2[/cyan]. Nghiên cứu từ khóa theo danh mục")

    choice = Prompt.ask("  Chọn", choices=["1", "2"])

    if choice == "1":
        console.print("\n[dim]Dán nội dung cần phân tích (kết thúc bằng dòng trống + Enter):[/dim]")
        lines = []
        while True:
            line = input()
            if line == "":
                break
            lines.append(line)
        content = "\n".join(lines)
        product_type = Prompt.ask("  Loại sản phẩm (tùy chọn)", default="")

        with console.status("[yellow]Đang phân tích SEO...[/yellow]"):
            result = agent.analyze_content(content, product_type)

    else:
        category = Prompt.ask("  Nhập danh mục sản phẩm (ví dụ: sofa, giường ngủ)")
        with console.status("[yellow]Đang nghiên cứu từ khóa...[/yellow]"):
            result = agent.research_keywords(category)

    console.print(Panel(Markdown(result), title="📊 Báo cáo SEO", border_style="yellow"))


def run_customer_support(agent: CustomerSupportAgent):
    console.print(Panel(
        "[bold]💬 Customer Support Agent[/bold]\n[dim]Chat với khách hàng (gõ 'quit' để thoát, 'reset' để cuộc hội thoại mới)[/dim]",
        border_style="blue"
    ))

    while True:
        user_input = Prompt.ask("\n[bold cyan]Khách hàng[/bold cyan]")

        if user_input.lower() == "quit":
            break
        if user_input.lower() == "reset":
            agent.reset_conversation()
            console.print("[dim]✅ Đã reset cuộc hội thoại[/dim]")
            continue
        if not user_input.strip():
            continue

        with console.status("[blue]Đang soạn phản hồi...[/blue]"):
            response = agent.chat(user_input)

        console.print(Panel(
            response,
            title="[bold green]Tư vấn viên[/bold green]",
            border_style="green",
        ))


def run_product_catalog(agent: ProductCatalogAgent):
    console.print(Panel("[bold]📦 Product Catalog Agent[/bold]", border_style="magenta"))

    console.print("\n[bold]Chọn tác vụ:[/bold]")
    console.print("  [cyan]1[/cyan]. Tạo JSON-LD Schema Markup cho sản phẩm")
    console.print("  [cyan]2[/cyan]. Gợi ý cấu trúc danh mục WooCommerce")

    choice = Prompt.ask("  Chọn", choices=["1", "2"])

    if choice == "1":
        console.print("\n[bold]Nhập thông tin sản phẩm:[/bold]")
        product_name = Prompt.ask("  Tên sản phẩm")
        description = Prompt.ask("  Mô tả ngắn")
        price = Prompt.ask("  Giá (VNĐ, ví dụ: 5500000)")
        category = Prompt.ask("  Danh mục (ví dụ: Sofa phòng khách)")
        brand = Prompt.ask("  Thương hiệu", default="Nội thất Việt")
        sku = Prompt.ask("  SKU (tùy chọn)", default="")

        with console.status("[magenta]Đang tạo Schema Markup...[/magenta]"):
            result = agent.generate_schema(product_name, description, price, category, brand, sku)

    else:
        console.print("\n[dim]Nhập danh sách sản phẩm (mỗi dòng 1 sản phẩm, Enter 2 lần để kết thúc):[/dim]")
        lines = []
        while True:
            line = input()
            if line == "":
                break
            lines.append(line)
        product_list = "\n".join(lines)

        with console.status("[magenta]Đang phân tích danh mục...[/magenta]"):
            result = agent.suggest_catalog_structure(product_list)

    console.print(Panel(Markdown(result), title="📋 Kết quả", border_style="magenta"))


def run_marketing_copy(agent: MarketingCopyAgent):
    console.print(Panel("[bold]📣 Marketing Copy Agent[/bold]", border_style="red"))

    console.print("\n[bold]Chọn loại nội dung:[/bold]")
    console.print("  [cyan]1[/cyan]. Social Media Post (Facebook/Zalo/Instagram)")
    console.print("  [cyan]2[/cyan]. Email Campaign")
    console.print("  [cyan]3[/cyan]. Banner & Quảng cáo")

    choice = Prompt.ask("  Chọn", choices=["1", "2", "3"])

    if choice == "1":
        product_name = Prompt.ask("  Tên sản phẩm")
        platform = Prompt.ask(
            "  Nền tảng",
            choices=["Facebook", "Zalo", "Instagram", "TikTok"],
            default="Facebook",
        )
        goal = Prompt.ask("  Mục tiêu (Bán hàng/Tăng nhận diện/Ra mắt sản phẩm/Khuyến mãi)")
        key_message = Prompt.ask("  Thông điệp chính (tùy chọn)", default="")
        promotion = Prompt.ask("  Khuyến mãi (tùy chọn)", default="")

        with console.status("[red]Đang tạo nội dung...[/red]"):
            result = agent.create_social_post(product_name, platform, goal, key_message, promotion)

    elif choice == "2":
        campaign_name = Prompt.ask("  Tên chiến dịch")
        audience = Prompt.ask("  Đối tượng (ví dụ: khách đã mua, khách tiềm năng)")
        promotion = Prompt.ask("  Chi tiết ưu đãi")

        with console.status("[red]Đang tạo email campaign...[/red]"):
            result = agent.create_email_campaign(campaign_name, audience, promotion)

    else:
        product = Prompt.ask("  Sản phẩm/Danh mục")
        promotion = Prompt.ask("  Khuyến mãi")
        deadline = Prompt.ask("  Deadline (tùy chọn, ví dụ: 31/12/2024)", default="")

        with console.status("[red]Đang tạo banner copy...[/red]"):
            result = agent.create_banner_copy(product, promotion, deadline)

    console.print(Panel(Markdown(result), title="📣 Nội dung Marketing", border_style="red"))


def main():
    show_welcome()

    client = get_client()

    # Khởi tạo tất cả agents (system prompts sẽ được cache sau request đầu tiên)
    with console.status("[dim]Đang khởi tạo agents...[/dim]"):
        agents = {
            "1": ContentGeneratorAgent(client),
            "2": SEOOptimizerAgent(client),
            "3": CustomerSupportAgent(client),
            "4": ProductCatalogAgent(client),
            "5": MarketingCopyAgent(client),
        }

    console.print("[green]✅ Đã sẵn sàng![/green]\n")

    handlers = {
        "1": lambda: run_content_generator(agents["1"]),
        "2": lambda: run_seo_optimizer(agents["2"]),
        "3": lambda: run_customer_support(agents["3"]),
        "4": lambda: run_product_catalog(agents["4"]),
        "5": lambda: run_marketing_copy(agents["5"]),
    }

    while True:
        show_menu()
        choice = Prompt.ask("\n[bold]Chọn agent[/bold]", choices=["0", "1", "2", "3", "4", "5"])

        if choice == "0":
            console.print("\n[cyan]👋 Tạm biệt![/cyan]")
            break

        try:
            handlers[choice]()
        except KeyboardInterrupt:
            console.print("\n[dim]Đã hủy.[/dim]")
        except Exception as e:
            console.print(f"\n[red]❌ Lỗi: {e}[/red]")

        console.print()
        if not Confirm.ask("Tiếp tục sử dụng?", default=True):
            console.print("\n[cyan]👋 Tạm biệt![/cyan]")
            break


if __name__ == "__main__":
    main()
