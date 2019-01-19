using System.Collections;
using System.Collections.Generic;
using UnityEngine;

public class HealSpell : MonoBehaviour {

	[HideInInspector] public float healAmount = 20f;
	[HideInInspector] public float manaCost = 20f;

	ParticleSystem particle;
	public void activeSpell()
	{
		if (MayaMove.instance.Mana < manaCost)
			return;
		MayaMove.instance.Mana -= manaCost;
		MayaMove.instance.HP += healAmount;
		if (MayaMove.instance.HP > MayaMove.instance.maxHP)
			MayaMove.instance.HP = MayaMove.instance.maxHP;
		particle.Play();
	}

	void Start()
	{
		particle = transform.GetChild(0).GetComponent<ParticleSystem>();
	}
	public void addSpell(int c)
	{
		if (c == 1)
		{
			SpellsTab.instance.spell1.RemoveAllListeners();
			SpellsTab.instance.spell1.AddListener(activeSpell);
		}
		if (c == 2)
		{
			SpellsTab.instance.spell2.RemoveAllListeners();
			SpellsTab.instance.spell2.AddListener(activeSpell);	
		}
		if (c == 3)
		{
			SpellsTab.instance.spell3.RemoveAllListeners();
			SpellsTab.instance.spell3.AddListener(activeSpell);
		}
		if (c == 4)
		{
			SpellsTab.instance.spell4.RemoveAllListeners();
			SpellsTab.instance.spell4.AddListener(activeSpell);
		}
	}
}
