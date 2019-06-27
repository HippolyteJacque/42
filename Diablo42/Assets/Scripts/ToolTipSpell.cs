using System.Collections;
using System.Collections.Generic;
using UnityEngine;
using UnityEngine.UI;

public class ToolTipSpell : MonoBehaviour {

	[SerializeField] int spellId;

	Text m_text;
	public CanvasGroup mySpellCanvas;
	//public TextMesh mySpellTip;
	//public GameObject mySpell;

	void Start()
	{
		m_text = transform.GetChild(1).GetChild(1).GetComponent<Text>();
	}

 	public void DisplaySpellTip(){
 		mySpellCanvas.alpha = 1f;
		if (spellId == 0)
		{
			m_text.text = "FireBall :\n" + "ManaCost: " + SpellsTab.instance.fireball.manaCost
			 + "\ndamage: " + SpellsTab.instance.fireball.damage +  "\n Upgrade: +10 damage" ;
		}
		if (spellId == 1)
		{
			m_text.text =  "Heal :\n" + "ManaCost: " + SpellsTab.instance.healSpell.manaCost
			 + "\nhealAmount: " + SpellsTab.instance.healSpell.healAmount +  "\n Upgrade: +5 healAmount" ;
		}
		if (spellId == 2)
		{
			m_text.text = "BurstBall(passif) : \n" + "+ " + (SpellsTab.instance.BurstballLvl * 15).ToString() + "damage to fireBall"  + "\n Upgrade: +15 damageIncrease" ;;
		}
		if (spellId == 3)
		{
			m_text.text = "FireWalk(passif) : \n" + "+ " + (SpellsTab.instance.FirewalkLvl * 1).ToString() + " movement speed" +  "\n Upgrade: +1 speed" ; ;
		}
		if (spellId == 4)
		{
			m_text.text = "FireArmor :\n" + "ManaCost: " + SpellsTab.instance.fireShield.manaCost
			 +  "/Sec\ndamage: " + SpellsTab.instance.fireShield.damage +  "\n Upgrade: +5 damage" ;
		}
		if (spellId == 5)
		{
			m_text.text = "FireSpike :\n" + "ManaCost: " + SpellsTab.instance.aoeSpell.manaCost
			 +  "\ndamage: " + SpellsTab.instance.aoeSpell.damage +  "\n Upgrade: +100 damage" ;
		}
 	}

 	public void HideSpellTip(){
		mySpellCanvas.alpha = 0f;
 	}
}
